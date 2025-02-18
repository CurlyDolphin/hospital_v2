<?php

namespace App\Service;

use App\Dto\Patient\CreatePatientDto;
use App\Dto\Patient\IdentifyPatientDto;
use App\Entity\Patient;
use App\Enum\GenderEnum;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatientService
{
    public function __construct(
        private readonly PatientRepository      $patientRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface     $validator,
        private readonly SerializerInterface    $serializer
    ) {}

    public function getPatients(): string
    {
        $patients = $this->patientRepository->findAll();

        return $this->serializer->serialize($patients, 'json', ["groups" => "patient:read"]);
    }

    public function getPatientInfo(int $patientId): string
    {
        $patient = $this->patientRepository->findPatientInfo($patientId);

        if (!$patient) {
            throw new EntityNotFoundException('Пациент не найден');
        }

        return $this->serializer->serialize(
            $patient,
            'json',
            ['groups' => 'patient:read']
        );
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

        $patient = $this->patientRepository->find($id);

        if (!$patient) {
            throw new EntityNotFoundException("Patient with id $id not found");
        }

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

    public function deletePatient(int $id): void
    {
        $patient = $this->patientRepository->find($id);

        if (!$patient) {
            throw new EntityNotFoundException('Patient not found');
        }

        $this->entityManager->remove($patient);
        $this->entityManager->flush();
    }
}
