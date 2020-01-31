<?php

namespace App\Service\InstanceGenerator;

use App\Entity\Training;

interface ITrainingInstanceGenerator
{
    public function generate(Training $training);
}
