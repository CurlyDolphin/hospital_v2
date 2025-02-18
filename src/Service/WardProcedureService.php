<?php

namespace App\Service;

use App\Dto\WardProcedure\UpdateWardProcedureDto;
use App\Entity\Procedure;
use App\Entity\WardProcedure;
use App\Repository\WardProcedureRepository;
use App\Repository\WardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WardProcedureService
{
    public function __construct(
        private readonly EntityManagerInterface  $entityManager,
        private readonly WardRepository          $wardRepository,
        private readonly WardProcedureRepository $wardProcedureRepository,
        private readonly SerializerInterface     $serializer,
        private readonly ValidatorInterface      $validator,
    ) {}

    public function getWardProcedures(int $wardId): string
    {
        $wardProcedures = $this->wardProcedureRepository->findByWardWithProcedureOrdered($wardId);

        if (empty($wardProcedures)) {
            throw new EntityNotFoundException('Лечебный план не найден для заданной палаты');
        }

        return $this->serializer->serialize(
            $wardProcedures,
            'json',
            ['groups' => 'ward_procedure:read']
        );
    }

    public function updateWardProcedures(int $wardId, UpdateWardProcedureDto $dto): void
    {
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $violation) {
                $errorMessages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            }
            throw new \InvalidArgumentException(implode('; ', $errorMessages));
        }

        $ward = $this->wardRepository->find($wardId);
        if (!$ward) {
            throw new EntityNotFoundException('Палата не найдена');
        }

        foreach ($dto->procedures as $procedureData) {
            $procedure = $this->entityManager
                ->getRepository(Procedure::class)
                ->find($procedureData['procedure_id']);
            if (!$procedure) {
                throw new EntityNotFoundException(
                    sprintf('Процедура с id %d не найдена', $procedureData['procedure_id'])
                );
            }

            $wardProcedure = new WardProcedure();
            $wardProcedure->setWard($ward);
            $wardProcedure->setProcedure($procedure);
            $wardProcedure->setSequence((int)$procedureData['sequence']);

            $this->entityManager->persist($wardProcedure);
        }

        $this->entityManager->flush();
    }
}