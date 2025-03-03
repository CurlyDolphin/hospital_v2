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
        private readonly EntityManagerInterface $entityManager,
        private readonly WardRepository $wardRepository,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function getWards(): string
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

    public function updateWard(int $id, CreateWardDto $dto): Ward
    {
        $ward = $this->findWardOrFail($id);

        $ward->setWardNumber($dto->wardNumber);
        $ward->setDescription($dto->description);

        $this->entityManager->persist($ward);
        $this->entityManager->flush();

        return $ward;
    }

    /**
     * @return array{wardNumber: int, patients: array<int, array{id: int, name: string, lastName: string}>}
     */
    public function getWardInfo(int $id): array
    {
        $ward = $this->findWardOrFail($id);

        $patients = [];

        foreach ($ward->getHospitalizations() as $hospitalization) {
            $patient = $hospitalization->getPatient();
            $patients[] = [
                'id' => $patient->getId(),
                'name' => $patient->getName(),
                'lastName' => $patient->getLastName(),
            ];
        }

        return [
            'wardNumber' => $ward->getWardNumber(),
            'patients' => $patients,
        ];
    }

    public function deleteWard(int $id): void
    {
        $ward = $this->findWardOrFail($id);

        foreach ($ward->getHospitalizations() as $hospitalization) {
            $hospitalization->setDeletedAt(new \DateTime('now'));
        }

        foreach ($ward->getWardProcedures() as $wardProcedure) {
            $this->entityManager->remove($wardProcedure);
        }

        $ward->setDeletedAt(new \DateTime('now'));

        $this->entityManager->persist($ward);
        $this->entityManager->flush();
    }

    public function findWardOrFail(int $id): Ward
    {
        $ward = $this->wardRepository->find($id);
        if (!$ward) {
            throw new EntityNotFoundException('Ward not found');
        }

        return $ward;
    }
}
