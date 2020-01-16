<?php

namespace App\Repository;

use App\Entity\Excercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Excercise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Excercise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Excercise[]    findAll()
 * @method Excercise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Excercise::class);
    }

    // /**
    //  * @return Excercise[] Returns an array of Excercise objects
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
    public function findOneBySomeField($value): ?Excercise
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
