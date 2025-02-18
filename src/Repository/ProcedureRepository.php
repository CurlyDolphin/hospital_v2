<?php

namespace App\Repository;

use App\Dto\Procedure\ProcedureInfoDto;
use App\Entity\Procedure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Procedure>
 */
class ProcedureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Procedure::class);
    }

    public function findProcedureInfo(int $id): ?ProcedureInfoDto
    {
        $procedure = $this->createQueryBuilder('p')
            ->leftJoin('p.wardProcedures', 'wp')
            ->addSelect('wp')
            ->leftJoin('wp.ward', 'w')
            ->addSelect('w')
            ->leftJoin('w.hospitalizations', 'h')
            ->addSelect('h')
            ->leftJoin('h.patient', 'pat')
            ->addSelect('pat')
            ->where('p.id = :id')
            ->andWhere('h.dischargeDate IS NULL')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$procedure) {
            return null;
        }

        $procedureDTO = new ProcedureInfoDto(
            $procedure->getId(),
            $procedure->getName(),
            $procedure->getDescription()
        );

        foreach ($procedure->getWardProcedures() as $wardProcedure) {
            $ward = $wardProcedure->getWard();
            $procedureDTO->addWard($ward->getWardNumber());

            foreach ($ward->getHospitalizations() as $hospitalization) {
                $patient = $hospitalization->getPatient();
                $procedureDTO->addPatient($patient->getName() . ' ' . $patient->getLastName());
            }
        }

        return $procedureDTO;
    }


}
