<?php

namespace App\Tests\Service\TrainingManager;

use PHPUnit\Framework\TestCase;
use App\Service\TrainingManager\DoctrineTrainingInstanceManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\InstanceGenerator\TrainingInstanceGenerator;
use App\Entity\Training;
use App\Entity\TrainingInstance;
use App\Service\TrainingManager\Exception\TrainingAlreadyStartedException;
use App\Service\TrainingManager\Exception\TrainingNotStartedException;

class DoctrineTrainingInstanceManagerTest extends TestCase
{
    /**
     * @var DoctrineTrainingInstanceManager
     */
    private $testSubject;
    /**
     * @var EntityManagerInterface
     */
    private $entityManagerMock;
    /**
     * @var TrainingInstanceGenerator
     */
    private $generatorMock;
    private $trainingInstance;
    private $training;

    public function testStartTrainingPersistsNewTrainingInstance()
    {
        $this->expectPersistingOneTrainingInstance();
        $this->startTraining();
    }

    public function testRestartTrainingPersistsNewTrainingInstance()
    {
        $this->trainingInstanceIsAlreadyAssignedToTraining();
        $this->expectPersistingOneTrainingInstance();
        $this->restartTraining();
    }

    public function testStartTrainingThrowsExceptionIfTrainingIsAlreadyStarted()
    {
        $this->trainingInstanceIsAlreadyAssignedToTraining();
        $this->expectException(TrainingAlreadyStartedException::class);
        $this->startTraining();
    }

    public function testFinishTrainingThrowsExceptionIfTrainingIsNotStarted()
    {
        $this->expectException(TrainingNotStartedException::class);
        $this->finishTraining();
    }

    public function testRestartTrainingThrowsExceptionIfTrainingIsNotStarted()
    {
        $this->expectException(TrainingNotStartedException::class);
        $this->restartTraining();
    }

    public function testFinishTrainingRemovesTrainingInstance()
    {
        $this->trainingInstanceIsAlreadyAssignedToTraining();
        $this->expectRemovingOneTrainingInstance();
        $this->finishTraining();
        $this->assertTrainingHasNoInstanceAttached();
    }

    public function testRestartTrainingRemovesTrainingInstance()
    {
        $this->trainingInstanceIsAlreadyAssignedToTraining();
        $this->expectRemovingOneTrainingInstance();
        $this->restartTraining();
    }

    protected function setUp(): void
    {
        $this->createTraining();
        $this->createTrainingInstance();
        $this->createEntityManagerMock();
        $this->createGeneratorMock();
        $this->testSubject = new DoctrineTrainingInstanceManager(
                $this->entityManagerMock,
                $this->generatorMock
        );
    }

    private function createTraining()
    {
        $this->training = new Training();
    }

    private function createTrainingInstance()
    {
        $this->trainingInstance = new TrainingInstance();
    }

    private function createEntityManagerMock()
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
    }

    private function createGeneratorMock()
    {
        $this->generatorMock = $this->createMock(TrainingInstanceGenerator::class);
        $this->generatorMock->expects($this->any())
                ->method('generate')
                ->willReturn($this->trainingInstance);
    }

    private function startTraining()
    {
        return $this->testSubject->startTraining($this->training);
    }

    private function restartTraining()
    {
        return $this->testSubject->restartTraining($this->training);
    }

    private function finishTraining()
    {
        return $this->testSubject->finishTraining($this->training);
    }

    private function expectPersistingOneTrainingInstance()
    {
        $this->entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->trainingInstance);
        $this->entityManagerMock->expects($this->any())
                ->method('flush');
    }

    private function expectRemovingOneTrainingInstance()
    {
        $this->entityManagerMock->expects($this->once())
                ->method('remove')
                ->with($this->trainingInstance);
        $this->entityManagerMock->expects($this->any())
                ->method('flush');
    }

    private function trainingInstanceIsAlreadyAssignedToTraining()
    {
        $this->training->setTrainingInstance($this->trainingInstance);
    }

    private function assertTrainingHasNoInstanceAttached()
    {
        $this->assertNull($this->training->getTrainingInstance());
    }

}
