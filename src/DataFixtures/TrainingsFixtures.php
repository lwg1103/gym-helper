<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Excercise;
use App\Entity\Training;

class TrainingsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $trainingMon = $this->createTraining("Monday");
        $manager->persist($trainingMon);
        
        $excercise1 = $this->createExcercise("exc1", 30, 12);
        $excercise1->setTraining($trainingMon);
        $manager->persist($excercise1);
        
        $excercise2 = $this->createExcercise("exc2", 45, 10);
        $excercise2->setTraining($trainingMon);
        $manager->persist($excercise2);

        $manager->flush();
    }
    
    private function createTraining($name)
    {
        $training = new Training();
        $training->setName($name);
        
        return $training;
    }
    
    private function createExcercise($name, $weight, $repeats, $series=3, $breakTime=60, $min=10, $max=15)
    {
        $excercise = new Excercise();
        $excercise->setName($name)
                ->setWeight($weight)
                ->setRepeats($repeats)
                ->setSeries($series)
                ->setBreakTime($breakTime)
                ->setMin($min)
                ->setMax($max);
        
        return $excercise;
        
    }
}
