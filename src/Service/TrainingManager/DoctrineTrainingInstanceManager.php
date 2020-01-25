<?php

namespace App\Service\TrainingManager;

use App\Entity\Training;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\InstanceGenerator\TrainingInstanceGenerator;
use App\Service\TrainingManager\Exception\TrainingAlreadyStartedException;
use App\Service\TrainingManager\Exception\TrainingNotStartedException;

class DoctrineTrainingInstanceManager implements ITrainingInstanceManager
{
    /**
     * @var EntityManagerInterface 
     */
    private $entityManager;
    /**
     * @var TrainingInstanceGenerator
     */
    private $generator;

    public function __construct(EntityManagerInterface $entityManager, TrainingInstanceGenerator $generator)
    {
        $this->entityManager = $entityManager;
        $this->generator     = $generator;
    }

    public function finishTraining(Training $training)
    {
        $trainingInstance = $training->getTrainingInstance();

        if (!$trainingInstance)
        {
            throw new TrainingNotStartedException();
        }

        $this->entityManager->remove($trainingInstance);
        $this->entityManager->flush();

        $training->setTrainingInstance(null);
    }

    public function restartTraining(Training $training)
    {
        $this->finishTraining($training);
        $this->startTraining($training);
    }

    public function startTraining(Training $training)
    {
        if ($training->getTrainingInstance())
        {
            throw new TrainingAlreadyStartedException();
        }

        $trainingInstance = $this->generator->generate($training);

        $this->entityManager->persist($trainingInstance);
        $this->entityManager->flush();
    }

}
