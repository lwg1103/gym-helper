<?php

namespace App\Repository;

use App\Entity\TrainingInstance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TrainingInstance|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingInstance|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingInstance[]    findAll()
 * @method TrainingInstance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingInstanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingInstance::class);
    }

    // /**
    //  * @return TrainingInstance[] Returns an array of TrainingInstance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrainingInstance
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
