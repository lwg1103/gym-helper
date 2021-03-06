<?php

namespace App\Repository;

use App\Entity\TrainingReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TrainingReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingReport[]    findAll()
 * @method TrainingReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingReport::class);
    }

    public function findLastReportForTraining($training): ?TrainingReport
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.baseTraining = :val')
            ->setParameter('val', $training)
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult()[0]
        ;
    }
}
