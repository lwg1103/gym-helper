<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\TrainingInstance;
use App\Entity\ExcerciseInstance;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TrainingInstanceFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $trainingInstance = new TrainingInstance();
        $trainingInstance->setBaseTraining($this->getOtherUserTraining($manager));
        $manager->persist($trainingInstance);
        
        $excerciseInstance = new ExcerciseInstance();
        $excerciseInstance->setBaseExcercise($this->getExcercise($manager))
                ->setTrainingInstance($trainingInstance)
                ->setBreakTime(30)
                ->setName("name")
                ->setRepeats(15)
                ->setResult(2)
                ->setWeight(45);
        $manager->persist($excerciseInstance);
        $manager->flush();
    }

    private function getOtherUserTraining(ObjectManager $manager)
    {
        $user = $manager->getRepository(\App\Entity\User::class)
                        ->findOneBy(['email' => "user2@ex.com"]);
        
        return $manager->getRepository(\App\Entity\Training::class)
                        ->findOneBy(['user' => $user]);
    }
    
    private function getExcercise(ObjectManager $manager)
    {
        $training = $this->getOtherUserTraining($manager);
        
        return $manager->getRepository(\App\Entity\Excercise::class)
                        ->findOneBy(['training' => $training]);
    }

    public function getDependencies()
    {
        return array(
            TrainingsFixtures::class,
            UserFixtures::class
        );
    }

}
