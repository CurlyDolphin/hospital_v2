<?php

namespace App\Service;

use App\Dto\Ward\CreateWardDto;
use App\Entity\Ward;
use App\Repository\WardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\SerializerInterface;

class WardService
{
    public function __construct(
        private readonly EntityManagerInterface  $entityManager,
        private readonly WardRepository          $wardRepository,
        private readonly SerializerInterface     $serializer,
    ) {}

    public function getWards()
    {
        $wards = $this->wardRepository->findAll();

        return $this->serializer->serialize($wards, 'json', ['groups' => 'ward:read']);
    }

    public function createWard(CreateWardDto $dto): Ward
    {
        $ward = new Ward();
        $ward->setWardNumber($dto->wardNumber);
        $ward->setDescription($dto->description);

        $this->entityManager->persist($ward);
        $this->entityManager->flush();

        return $ward;
    }

    public function updateWard(int $id, CreateWardDto $dto)
    {
        $ward = $this->wardRepository->find($id);

        if (!$ward) {
            throw new EntityNotFoundException('Ward not found');
        }

        $ward->setWardNumber($dto->wardNumber);
        $ward->setDescription($dto->description);

        $this->entityManager->persist($ward);
        $this->entityManager->flush();

        return $ward;
    }

    public function getWardInfo(int $wardId): array
    {
        $ward = $this->wardRepository->findWardWithPatients($wardId);

        if (!$ward) {
            throw new EntityNotFoundException('Ward not found');
        }

        $patients = [];

        foreach ($ward->getHospitalizations() as $hospitalization) {
            $patient = $hospitalization->getPatient();
            $patients[] = [
                'id'       => $patient->getId(),
                'name'     => $patient->getName(),
                'lastName' => $patient->getLastName(),
            ];
        }

        return [
            'wardNumber' => $ward->getWardNumber(),
            'patients'   => $patients,
        ];
    }

    public function deleteWard(int $wardId): void
    {
        $ward = $this->wardRepository->find($wardId);

        if (!$ward) {
            throw new \Exception("Палата с идентификатором {$wardId} не найдена.");
        }


        foreach ($ward->getHospitalizations() as $hospitalization) {
            $hospitalization->setDeletedAt(new \DateTime('now'));
        }

        foreach ($ward->getWardProcedures() as $wardProcedure) {
            $wardProcedure->setDeletedAt(new \DateTime('now'));
        }

        $ward->setDeletedAt(new \DateTime('now'));

        $this->entityManager->persist($ward);
        $this->entityManager->flush();
    }
}