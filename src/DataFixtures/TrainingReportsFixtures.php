<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\TrainingReport;
use App\Entity\ExcerciseReport;
use App\Model\ExcerciseInstanceResult;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TrainingReportsFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $excerciseReport1 = new ExcerciseReport();
        $excerciseReport1->setName("exc 1")
                ->setSeries(3)
                ->setResult(ExcerciseInstanceResult::Ok)
                ->setWeight(25)
                ->setBaseExcercise($this->getFirstExistingExcercise($manager));

        $excerciseReport2 = new ExcerciseReport();
        $excerciseReport2->setName("exc 2")
                ->setSeries(3)
                ->setResult(ExcerciseInstanceResult::TooHard)
                ->setWeight(35)
                ->setBaseExcercise($this->getFirstExistingExcercise($manager));

        $trainingReport = new TrainingReport();
        $trainingReport->setName("training 1")
                ->addExcerciseReport($excerciseReport1)
                ->addExcerciseReport($excerciseReport2);

        $manager->persist($trainingReport);
        $manager->flush();
    }

    private function getFirstExistingExcercise(ObjectManager $manager)
    {
        return $manager->getRepository(\App\Entity\Excercise::class)
                        ->findAll()[0];
    }

    public function getDependencies()
    {
        return array(
            TrainingsFixtures::class
        );
    }

}
