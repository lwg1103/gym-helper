<?php

namespace App\Service\TrainingManager;

use App\Entity\Training;

interface ITrainingInstanceManager
{
    public function startTraining(Training $training);
    public function finishTraining(Training $training);
    public function restartTraining(Training $training);
}
