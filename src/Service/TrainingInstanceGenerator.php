<?php

namespace App\Service;

use App\Entity\Training;
use App\Entity\TrainingInstance;
use \App\Entity\ExcerciseInstance;

class TrainingInstanceGenerator
{

    public function generate(Training $training)
    {
        $trainingInstance = new TrainingInstance();
        $trainingInstance->setBaseTraining($training);

        foreach ($training->getExcercises() as $excercise)
        {
            for ($i = 0; $i < $excercise->getSeries(); $i++)
            {
                $excerciseInstance = new ExcerciseInstance();

                $excerciseInstance->setName($excercise->getName())
                        ->setWeight($excercise->getWeight())
                        ->setRepeats($excercise->getRepeats())
                        ->setBreakTime($excercise->getBreakTime())
                        ->setResult(0)
                        ->setTrainingInstance($trainingInstance);

                $trainingInstance->addExcercise($excerciseInstance);
            }
        }

        return $trainingInstance;
    }

}
