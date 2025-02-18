<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    public function findPatientInfo(int $patientId): ?Patient
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.hospitalizations', 'h')
            ->addSelect('h')
            ->leftJoin('h.ward', 'w')
            ->addSelect('w')
            ->where('p.id = :patientId')
            ->setParameter('patientId', $patientId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
