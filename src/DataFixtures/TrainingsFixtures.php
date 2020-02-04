<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Excercise;
use App\Entity\Training;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TrainingsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->loadMonday($manager);
        $this->loadWednesday($manager);
        $this->loadFriday($manager);
        $this->loadSecondUserTraining($manager);
    }
    
    private function loadMonday(ObjectManager $manager)
    {
        $trainingMon = $this->createTraining("Day 1", $manager);
        $manager->persist($trainingMon);
        
        $excercise1 = $this->createExcercise("exc1", 30, 12);
        $excercise1->setTraining($trainingMon);
        $manager->persist($excercise1);
        
        $excercise2 = $this->createExcercise("exc2", 45, 10);
        $excercise2->setTraining($trainingMon);
        $manager->persist($excercise2);

        $manager->flush();
    }
    
    private function loadWednesday(ObjectManager $manager)
    {
        $trainingMon = $this->createTraining("Day 2", $manager);
        $manager->persist($trainingMon);
        
        $excercise1 = $this->createExcercise("exc1", 30, 12);
        $excercise1->setTraining($trainingMon);
        $manager->persist($excercise1);
        
        $excercise2 = $this->createExcercise("exc2", 45, 10);
        $excercise2->setTraining($trainingMon);
        $manager->persist($excercise2);

        $manager->flush();
    }
    
    private function loadFriday(ObjectManager $manager)
    {
        $trainingMon = $this->createTraining("Day 3", $manager);
        $manager->persist($trainingMon);
        
        $excercise1 = $this->createExcercise("exc1", 30, 12);
        $excercise1->setTraining($trainingMon);
        $manager->persist($excercise1);
        
        $excercise2 = $this->createExcercise("exc2", 45, 10);
        $excercise2->setTraining($trainingMon);
        $manager->persist($excercise2);

        $manager->flush();
    }
    
    private function loadSecondUserTraining(ObjectManager $manager)
    {
        $trainingMon = $this->createTraining("User2 training", $manager, 1);
        $manager->persist($trainingMon);
        
        $excercise1 = $this->createExcercise("exc11", 30, 12);
        $excercise1->setTraining($trainingMon);
        $manager->persist($excercise1);
        
        $excercise2 = $this->createExcercise("exc12", 45, 10);
        $excercise2->setTraining($trainingMon);
        $manager->persist($excercise2);

        $manager->flush();
    }
    
    private function createTraining($name, ObjectManager $manager, $userNumber = 0)
    {
        $training = new Training();
        $training->setName($name)
                ->setUser($this->getExistingUser($manager, $userNumber));
        
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

    private function getExistingUser(ObjectManager $manager, $nr = 0)
    {
        return $manager->getRepository(\App\Entity\User::class)
                        ->findAll()[$nr];
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class
        );
    }
}
