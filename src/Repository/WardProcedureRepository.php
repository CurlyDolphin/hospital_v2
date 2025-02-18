<?php

namespace App\Repository;

use App\Entity\WardProcedure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WardProcedure>
 */
class WardProcedureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WardProcedure::class);
    }

    public function findByWardWithProcedureOrdered(int $wardId): array
    {
        return $this->createQueryBuilder('wp')
            ->addSelect('p')
            ->join('wp.procedure', 'p')
            ->where('wp.ward = :wardId')
            ->setParameter('wardId', $wardId)
            ->orderBy('wp.sequence', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
