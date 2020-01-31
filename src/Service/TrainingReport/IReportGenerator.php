<?php

namespace App\Service\TrainingReport;

use App\Entity\TrainingReport;
use App\Entity\TrainingInstance;

interface IReportGenerator
{
    public function generate(TrainingInstance $trainingInstance): TrainingReport;
}
