<?php

namespace App\Tests\Service\TrainingReport;

use PHPUnit\Framework\TestCase;
use App\Service\TrainingReport\ReportGenerator;
use App\Entity\TrainingInstance;
use App\Entity\ExcerciseInstance;
use App\Model\ExcerciseInstanceResult;
use App\Entity\TrainingReport;

class ReportGeneratorTest extends TestCase
{
    /**
     * @var DoctrineReportGenerator 
     */
    private $testSubject;
    /**
     * @var TrainingInstance
     */
    private $trainingInstance;
    /**
     * @var ExcerciseInstance[] 
     */
    private $excerciseInstances;
    /**
     * @var TrainingReport
     */
    private $trainingReport;

    public function testGenerateReturnsTrainingReport()
    {
        $this->generateReport();
        $this->assertInstanceOf(
                TrainingReport::class,
                $this->trainingReport
        );
    }

    public function testGenerateCalculateProperExcerciseResult()
    {
        $this->trainingHasExcerciseInstance("excercise 1", 15, 12, 4);
        $this->trainingHasExcerciseInstance("excercise 2", 45, 8, 3);

        //excercise 1
        $this->excerciseNResultIs(0, ExcerciseInstanceResult::TooEasy);
        $this->excerciseNResultIs(1, ExcerciseInstanceResult::TooEasy);
        $this->excerciseNResultIs(2, ExcerciseInstanceResult::Ok);
        $this->excerciseNResultIs(3, ExcerciseInstanceResult::Ok);
        //excercise 2
        $this->excerciseNResultIs(4, ExcerciseInstanceResult::TooEasy);
        $this->excerciseNResultIs(5, ExcerciseInstanceResult::Ok);
        $this->excerciseNResultIs(6, ExcerciseInstanceResult::TooHard);

        $this->generateReport();

        $this->assertEquals(
                ExcerciseInstanceResult::Ok,
                $this->trainingReport->getExcerciseReports()->get(0)->getResult()
        );
        
        $this->assertEquals(
                ExcerciseInstanceResult::TooHard,
                $this->trainingReport->getExcerciseReports()->get(1)->getResult()
        );
    }

    protected function setUp(): void
    {
        $this->createTrainingInstances();
        $this->testSubject = new ReportGenerator();
    }

    private function createTrainingInstances()
    {
        $this->trainingInstance = new TrainingInstance();
    }

    private function generateReport()
    {
        $this->trainingReport = $this->testSubject->generate($this->trainingInstance);
    }

    private function trainingHasExcerciseInstance($name, $weight, $repeats, $series)
    {
        for ($i = 0; $i < $series; $i++)
        {
            $excercise = new ExcerciseInstance();

            $excercise->setName($name)
                    ->setWeight($weight)
                    ->setRepeats($repeats)
                    ->setResult(ExcerciseInstanceResult::Todo)
                    ->setBreakTime(60)
                    ->setTrainingInstance($this->trainingInstance)
                    ->setBaseExcercise(new \App\Entity\Excercise());

            $this->trainingInstance->addExcercise($excercise);

            $this->excerciseInstances[] = $excercise;
        }
    }

    private function excerciseNResultIs($n, $result)
    {
        $this->excerciseInstances[$n]->setResult($result);
    }

}
