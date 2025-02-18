<?php

namespace App\Repository;

use App\Entity\Ward;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ward>
 */
class WardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ward::class);
    }

    public function findWardWithPatients(int $id): ?Ward
    {
        return $this->createQueryBuilder('w')
            ->leftJoin('w.hospitalizations', 'h')
            ->addSelect('h')
            ->leftJoin('h.patient', 'p')
            ->addSelect('p')
            ->andWhere('w.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
