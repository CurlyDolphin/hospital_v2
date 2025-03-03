<?php

namespace App\Service;

use App\Dto\Hospitalization\AssignPatientDto;
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
        private readonly WardService $wardService,
        private readonly PatientService $patientService,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function assignPatientToWard(AssignPatientDto $dto): Patient
    {
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getPropertyPath().': '.$violation->getMessage();
            }
            throw new \InvalidArgumentException(implode('; ', $errorMessages));
        }

        $patient = $this->patientService->findPatientOrFail($dto->patientId);

        $ward = $this->wardService->findWardOrFail($dto->wardId);

        $hospitalization = new Hospitalization();
        $hospitalization->setPatient($patient);
        $hospitalization->setWard($ward);

        $patient->addHospitalization($hospitalization);
        $ward->addHospitalization($hospitalization);

        $this->entityManager->persist($hospitalization);
        $this->entityManager->flush();

        return $patient;
    }
}
