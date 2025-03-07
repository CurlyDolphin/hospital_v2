<?php

namespace App\Service;

use App\Dto\Patient\CreatePatientDto;
use App\Dto\Patient\IdentifyPatientDto;
use App\Dto\Patient\UpdatePatientDto;
use App\Entity\Hospitalization;
use App\Entity\Patient;
use App\Enum\GenderEnum;
use App\Repository\PatientRepository;
use App\Repository\WardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatientService
{
    public function __construct(
        private readonly PatientRepository $patientRepository,
        private readonly WardRepository $wardRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
    ) {
    }

    /** @return Patient[] */
    public function getPatients(): array
    {
        return $this->patientRepository->findAll();
    }

    public function getPatientInfo(int $patientId): Patient
    {
        return $this->findPatientOrFail($patientId);
    }

    public function createPatient(CreatePatientDto $dto): Patient
    {
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        $gender = GenderEnum::from($dto->gender);

        if (!$dto->isIdentified) {
            $dto->name = match ($gender) {
                GenderEnum::MALE => 'John',
                GenderEnum::FEMALE => 'Jane',
                GenderEnum::OTHER => 'Alex',
            };
            $dto->lastName = 'Doe';
        }

        $cardNumber = $dto->cardNumber ?? $this->generateNextCardNumber();
        if (isset($dto->cardNumber)) {
            $this->updateCardNumberSequence($cardNumber);
        }

        $patient = new Patient();
        $patient->setName($dto->name);
        $patient->setLastName($dto->lastName);
        $patient->setGender($gender);
        $patient->setIdentified($dto->isIdentified);
        $patient->setCardNumber($cardNumber);

        if ($dto->birthday) {
            $patient->setBirthday($dto->birthday);
        }

        $this->entityManager->persist($patient);
        $this->entityManager->flush();

        return $patient;
    }

    private function generateNextCardNumber(): int
    {
        return $this->entityManager->getConnection()->fetchOne("SELECT nextval('card_number_seq')");
    }

    private function updateCardNumberSequence(int $manualNumber): void
    {
        $this->entityManager->getConnection()->executeStatement(
            "SELECT setval('card_number_seq', GREATEST((SELECT MAX(card_number) FROM patient), :manualNumber) + 1, false)",
            ['manualNumber' => $manualNumber]
        );
    }

    public function identifyPatient(int $id, IdentifyPatientDto $dto): Patient
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        $patient = $this->findPatientOrFail($id);

        if ($dto->birthday) {
            $patient->setBirthday($dto->birthday);
        }

        $patient->setName($dto->name);
        $patient->setLastName($dto->lastName);
        $patient->setIdentified(true);

        $this->entityManager->persist($patient);
        $this->entityManager->flush();

        return $patient;
    }

    public function updatePatient(
        int $id,
        UpdatePatientDto $dto,
    ): Patient {
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getPropertyPath().': '.$violation->getMessage();
            }
            throw new \InvalidArgumentException(implode('; ', $errorMessages));
        }

        $patient = $this->findPatientOrFail($id);

        $patient->setName($dto->name);
        $patient->setLastName($dto->lastName);

        $ward = $this->wardRepository->find($dto->wardId);
        if (!$ward) {
            throw new EntityNotFoundException('Ward not found');
        }

        $currentHospitalization = $patient->getHospitalizations()->last();

        if ($currentHospitalization) {
            $currentHospitalization->setWard($ward);
        } else {
            $hospitalization = new Hospitalization();
            $hospitalization->setPatient($patient);
            $hospitalization->setWard($ward);

            $this->entityManager->persist($hospitalization);
        }

        $this->entityManager->flush();

        return $patient;
    }

    public function deletePatient(int $id): void
    {
        $patient = $this->findPatientOrFail($id);

        foreach ($patient->getHospitalizations() as $hospitalization) {
            $this->entityManager->remove($hospitalization);
        }

        $this->entityManager->remove($patient);
        $this->entityManager->flush();
    }

    public function findPatientOrFail(int $id): Patient
    {
        $patient = $this->patientRepository->find($id);
        if (!$patient) {
            throw new EntityNotFoundException('Patient not found');
        }

        return $patient;
    }
}
