<?php

namespace App\Service;

use App\Dto\Procedure\CreateProcedureDto;
use App\Dto\Procedure\ResponseProcedureInfoDto;
use App\Entity\Procedure;
use App\Entity\WardProcedure;
use App\Repository\ProcedureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\SerializerInterface;

class ProcedureService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProcedureRepository $procedureRepository,
    ) {
    }

    /**
     * @return Procedure[]
     */
    public function getProcedures(): array
    {
        return $this->procedureRepository->findAll();
    }

    public function getProcedureInfo(int $procedureId): ResponseProcedureInfoDto
    {
        $procedureDto = $this->procedureRepository->findProcedureInfo($procedureId);

        if (!$procedureDto) {
            throw new EntityNotFoundException('Процедура не найдена');
        }

        return $procedureDto;
    }

    public function createProcedure(CreateProcedureDto $dto): Procedure
    {
        $procedure = new Procedure();
        $procedure->setName($dto->name);
        $procedure->setDescription($dto->description);

        $this->entityManager->persist($procedure);
        $this->entityManager->flush();

        return $procedure;
    }

    public function updateProcedure(int $id, CreateProcedureDto $dto): Procedure
    {
        $procedure = $this->findProcedureOrFail($id);

        $procedure->setName($dto->name);
        $procedure->setDescription($dto->description);

        $this->entityManager->persist($procedure);
        $this->entityManager->flush();

        return $procedure;
    }

    public function deleteProcedure(int $id): void
    {
        $procedure = $this->findProcedureOrFail($id);

        $wardProcedures = $this->entityManager->getRepository(WardProcedure::class)->findBy(['procedure' => $procedure]);
        foreach ($wardProcedures as $wardProcedure) {
            $this->entityManager->remove($wardProcedure);
        }

        $this->entityManager->remove($procedure);
        $this->entityManager->flush();
    }

    public function findProcedureOrFail(int $id): Procedure
    {
        $procedure = $this->entityManager->getRepository(Procedure::class)->find($id);
        if (!$procedure) {
            throw new EntityNotFoundException('Procedure not found');
        }

        return $procedure;
    }
}
