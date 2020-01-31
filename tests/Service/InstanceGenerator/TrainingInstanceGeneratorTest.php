<?php

namespace App\Tests\Service\InstanceGenerator;

use PHPUnit\Framework\TestCase;
use App\Service\InstanceGenerator\TrainingInstanceGenerator;
use App\Entity\TrainingInstance;
use App\Entity\Training;
use App\Entity\Excercise;
use App\Model\ExcerciseInstanceResult;

class TrainingInstanceGeneratorTest extends TestCase
{
    /**
     * @var TrainingInstanceGenerator
     */
    private $testSubject;

    public function testGeneratesTrainingInstance()
    {
        $result = $this->runGenerator(new Training());
        $this->assertInstanceOf(TrainingInstance::class, $result);
    }

    public function testGeneratesTrainingInstanceWithProperBaseTraining()
    {
        $training = $this->thereIsTrainingWithTwoExcercises();
        $result   = $this->runGenerator($training);

        $this->assertEquals($training, $result->getBaseTraining());
    }

    public function testGeneratesTrainingInstanceWithProperName()
    {
        $training = $this->thereIsTrainingWithTwoExcercises();
        $result   = $this->runGenerator($training);

        $this->assertEquals($training->getName(), $result->getName());
    }

    public function testGeneratesTrainingInstanceWithEachSerieAsExcercise()
    {
        $training = $this->thereIsTrainingWithTwoExcercises();
        $result   = $this->runGenerator($training);

        $excercises = $result->getExcercises();
        $this->assertEquals(
                $training->getExcercises()[0]->getSeries() + $training->getExcercises()[1]->getSeries(),
                count($excercises)
        );
    }

    public function testGeneratesTrainingInstanceWithAllExcercises()
    {
        $training = $this->thereIsTrainingWithTwoExcercises();
        $result   = $this->runGenerator($training);

        $excercises = $result->getExcercises();

        foreach ($excercises as $key => $excercise)
        {
            $trKey = $key < $training->getExcercises()[0]->getSeries() ? 0 : 1;
            
            $this->assertEquals(
                    $training->getExcercises()[$trKey]->getName(),
                    $excercise->getName()
            );
            
            $this->assertEquals(
                    $training->getExcercises()[$trKey]->getWeight(),
                    $excercise->getWeight()
            );
            
            $this->assertEquals(
                    $training->getExcercises()[$trKey]->getRepeats(),
                    $excercise->getRepeats()
            );
            
            $this->assertEquals(
                    $training->getExcercises()[$trKey],
                    $excercise->getBaseExcercise()
            );
            
            $this->assertEquals(
                    ExcerciseInstanceResult::Todo,
                    $excercise->getResult()
            );
        }
    }

    protected function setUp(): void
    {
        $this->testSubject = new TrainingInstanceGenerator();
    }

    private function thereIsTrainingWithTwoExcercises()
    {
        $training = new Training();
        $training->setName("Day 1");

        $exc1 = new Excercise();
        $exc1->setName("exc1")
                ->setWeight(30)
                ->setRepeats(12)
                ->setSeries(3)
                ->setBreakTime(60)
                ->setMin(10)
                ->setMax(15)
                ->setTraining($training);
        $training->addExcercise($exc1);

        $exc2 = new Excercise();
        $exc2->setName("exc2")
                ->setWeight(45)
                ->setRepeats(10)
                ->setSeries(4)
                ->setBreakTime(90)
                ->setMin(8)
                ->setMax(12)
                ->setTraining($training);
        $training->addExcercise($exc2);

        return $training;
    }

    private function runGenerator($training)
    {
        return $this->testSubject->generate($training);
    }

}
