<?php

namespace App\Service\TrainingManager;

use App\Entity\ExcerciseInstance;

interface IExcerciseInstanceManager
{
    public function markAsTooEasy(ExcerciseInstance $excerciseInstance);
    public function markAsOk(ExcerciseInstance $excerciseInstance);
    public function markAsTooHard(ExcerciseInstance $excerciseInstance);
}
