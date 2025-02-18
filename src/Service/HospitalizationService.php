<?php

namespace App\Service;

use App\Dto\Hospitalization\AssignPatientDto;
use App\Dto\Hospitalization\UpdatePatientDto;
use App\Entity\Hospitalization;
use App\Entity\Patient;
use App\Repository\PatientRepository;
use App\Repository\WardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HospitalizationService
{
    public function __construct(
        private readonly PatientRepository      $patientRepository,
        private readonly WardRepository         $wardRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface     $validator,
    ) {}

    public function assignPatientToWard(AssignPatientDto $dto): Patient
    {
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        $patient = $this->patientRepository->find($dto->patientId);
        if (!$patient) {
            throw new EntityNotFoundException('Patient not found');
        }

        $ward = $this->wardRepository->find($dto->wardId);
        if (!$ward) {
            throw new EntityNotFoundException('Ward not found');
        }

        $hospitalization = new Hospitalization();
        $hospitalization->setPatient($patient);
        $hospitalization->setWard($ward);

        $patient->addHospitalization($hospitalization);
        $ward->addHospitalization($hospitalization);

        $this->entityManager->persist($hospitalization);
        $this->entityManager->flush();

        return $patient;
    }

    public function updatePatient(
        int $id,
        UpdatePatientDto $dto,
    ): Patient {

        $patient = $this->patientRepository->find($id);
        if (!$patient) {
            throw new EntityNotFoundException('Patient not found');
        }

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
}