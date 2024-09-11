<?php

namespace App\Repository;

use App\Entity\Recipes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipes>
 */
class RecipesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipes::class);
    }

    /**
     * Returns recipes by duration
     *
     * @param int $duration
     * @return array
     */
    public function findByDuration ($duration): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->where('r.duration <= :duration')
            ->leftJoin('r.category', 'c')
            ->setParameter('duration', $duration)
            ->orderBy('r.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getTotalDuration (): int
    {
        return $this->createQueryBuilder('r')
            ->select('SUM(r.duration) as totalDuration')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    //    /**
    //     * @return Recipes[] Returns an array of Recipes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipes
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
