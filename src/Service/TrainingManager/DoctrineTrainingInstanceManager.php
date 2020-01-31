<?php

namespace App\Service\TrainingManager;

use App\Entity\Training;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\InstanceGenerator\TrainingInstanceGenerator;
use App\Service\TrainingManager\Exception\TrainingAlreadyStartedException;
use App\Service\TrainingManager\Exception\TrainingNotStartedException;
use App\Service\TrainingReport\ReportGenerator;

class DoctrineTrainingInstanceManager implements ITrainingInstanceManager
{
    /**
     * @var EntityManagerInterface 
     */
    private $entityManager;
    /**
     * @var TrainingInstanceGenerator
     */
    private $trainingGenerator;
    /**
     * @var ReportGenerator 
     */
    private $reportGenerator;

    public function __construct(EntityManagerInterface $entityManager, TrainingInstanceGenerator $trainingGenerator, ReportGenerator $reportGenerator)
    {
        $this->entityManager     = $entityManager;
        $this->trainingGenerator = $trainingGenerator;
        $this->reportGenerator   = $reportGenerator;
    }

    public function finishTraining(Training $training, bool $withReport = true)
    {
        $trainingInstance = $training->getTrainingInstance();

        if (!$trainingInstance)
        {
            throw new TrainingNotStartedException();
        }

        if ($withReport)
        {
            $report = $this->reportGenerator->generate($trainingInstance);
            $this->entityManager->persist($report);
        }
        
        $this->entityManager->remove($trainingInstance);
        $this->entityManager->flush();

        $training->setTrainingInstance(null);
    }

    public function restartTraining(Training $training)
    {
        $this->finishTraining($training, false);
        $this->startTraining($training);
    }

    public function startTraining(Training $training)
    {
        if ($training->getTrainingInstance())
        {
            throw new TrainingAlreadyStartedException();
        }

        $trainingInstance = $this->trainingGenerator->generate($training);

        $this->entityManager->persist($trainingInstance);
        $this->entityManager->flush();
    }

}
