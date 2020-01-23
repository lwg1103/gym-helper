<?php

namespace App\Repository;

use App\Entity\ExcerciseInstance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExcerciseInstance|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExcerciseInstance|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExcerciseInstance[]    findAll()
 * @method ExcerciseInstance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcerciseInstanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExcerciseInstance::class);
    }

    // /**
    //  * @return ExcerciseInstance[] Returns an array of ExcerciseInstance objects
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
    public function findOneBySomeField($value): ?ExcerciseInstance
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
