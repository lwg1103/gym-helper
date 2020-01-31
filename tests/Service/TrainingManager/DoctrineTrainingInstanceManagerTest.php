<?php

namespace App\Tests\Service\TrainingManager;

use PHPUnit\Framework\TestCase;
use App\Service\TrainingManager\DoctrineTrainingInstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\InstanceGenerator\TrainingInstanceGenerator;
use App\Entity\Training;
use App\Entity\TrainingInstance;
use App\Service\TrainingManager\Exception\TrainingAlreadyStartedException;
use App\Service\TrainingManager\Exception\TrainingNotStartedException;
use App\Service\TrainingReport\ReportGenerator;

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
    private $trainingInstancegeneratorMock;
    private $trainingReportGeneratorMock;
    private $trainingInstance;
    private $trainingReport;
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
        $this->expectPersistingOneTrainingReport();
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
        $this->createTrainingReport();
        $this->createEntityManagerMock();
        $this->createTrainingInstancegeneratorMock();
        $this->createTrainingReportGeneratorMock();
        $this->testSubject = new DoctrineTrainingInstanceManager(
                $this->entityManagerMock,
                $this->trainingInstancegeneratorMock,
                $this->trainingReportGeneratorMock
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

    private function createTrainingReport()
    {
        $this->trainingReport = new \App\Entity\TrainingReport();
    }

    private function createEntityManagerMock()
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
    }

    private function createTrainingInstancegeneratorMock()
    {
        $this->trainingInstancegeneratorMock = $this->createMock(TrainingInstanceGenerator::class);
        $this->trainingInstancegeneratorMock->expects($this->any())
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
    
    private function expectPersistingOneTrainingReport()
    {
        $this->entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->trainingReport);
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
    
    private function createTrainingReportGeneratorMock()
    {
        $this->trainingReportGeneratorMock = $this->createMock(ReportGenerator::class);
        $this->trainingReportGeneratorMock->expects($this->any())
                ->method('generate')
                ->willReturn($this->trainingReport);
    }

}
