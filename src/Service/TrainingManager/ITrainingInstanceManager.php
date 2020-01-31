<?php

namespace App\Service\TrainingManager;

use App\Entity\Training;

interface ITrainingInstanceManager
{
    public function startTraining(Training $training);
    public function finishTraining(Training $training, bool $withReport = true);
    public function restartTraining(Training $training);
}
