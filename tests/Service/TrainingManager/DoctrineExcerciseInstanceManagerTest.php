<?php

namespace App\Tests\Service\TrainingManager;

use PHPUnit\Framework\TestCase;
use App\Service\TrainingManager\DoctrineExcerciseInstanceManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ExcerciseInstance;
use App\Model\ExcerciseInstanceResult;

class DoctrineExcerciseInstanceManagerTest extends TestCase
{
    /**
     * @var DoctrineExcerciseInstanceManager 
     */
    private $testSubject;
    /**
     * @var EntityManagerInterface
     */
    private $entityManagerMock;
    /**
     * @var ExcerciseInstance
     */
    private $excerciseInstance;

    public function testMarkAsOkUpdatesExcerciseInstance()
    {
        $this->excerciseInstanceResultIsTodo();
        $this->expectChangesWereSavedInDb();
        $this->testSubject->markAsOk($this->excerciseInstance);
        $this->assertEquals(
                ExcerciseInstanceResult::Ok,
                $this->excerciseInstance->getResult()
        );
    }

    public function testMarkAsTooHardUpdatesExcerciseInstance()
    {
        $this->excerciseInstanceResultIsTodo();
        $this->expectChangesWereSavedInDb();
        $this->testSubject->markAsTooHard($this->excerciseInstance);
        $this->assertEquals(
                ExcerciseInstanceResult::TooHard,
                $this->excerciseInstance->getResult()
        );
    }

    public function testMarkAsTooEasyUpdatesExcerciseInstance()
    {
        $this->excerciseInstanceResultIsTodo();
        $this->expectChangesWereSavedInDb();
        $this->testSubject->markAsTooEasy($this->excerciseInstance);
        $this->assertEquals(
                ExcerciseInstanceResult::TooEasy,
                $this->excerciseInstance->getResult()
        );
    }

    protected function setUp(): void
    {
        $this->createEntityManagerMock();
        $this->createExcerciseInstance();
        $this->testSubject = new DoctrineExcerciseInstanceManager(
                $this->entityManagerMock
        );
    }

    private function createEntityManagerMock()
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
    }

    private function createExcerciseInstance()
    {
        $this->excerciseInstance = new ExcerciseInstance();
    }

    private function excerciseInstanceResultIsTodo()
    {
        $this->excerciseInstance->setResult(ExcerciseInstanceResult::Todo);
    }

    private function expectChangesWereSavedInDb()
    {
        $this->entityManagerMock->expects($this->once())
                ->method('flush');
    }
}
