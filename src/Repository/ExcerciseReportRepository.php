<?php

namespace App\Repository;

use App\Entity\ExcerciseReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExcerciseReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExcerciseReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExcerciseReport[]    findAll()
 * @method ExcerciseReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcerciseReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExcerciseReport::class);
    }

    // /**
    //  * @return ExcerciseReport[] Returns an array of ExcerciseReport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExcerciseReport
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
