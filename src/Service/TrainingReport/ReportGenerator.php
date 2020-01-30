<?php

namespace App\Service\TrainingReport;

use App\Entity\TrainingReport;
use App\Entity\ExcerciseReport;
use App\Entity\TrainingInstance;
use App\Entity\ExcerciseInstance;

class ReportGenerator implements IReportGenerator
{

    public function generate(TrainingInstance $trainingInstance): TrainingReport
    {
        $trainingReport = new TrainingReport();

        $trainingReport->setBaseTraining($trainingInstance->getBaseTraining());

        $excerciseReports = $this->calculateExcercisesReports($trainingInstance->getExcercises());

        foreach ($excerciseReports as $excerciseReport)
        {
            $excerciseReport->setTrainingReport($trainingReport);
            $trainingReport->addExcerciseReport($excerciseReport);
        }

        return $trainingReport;
    }

    /**
     * @param ExcerciseInstance[] $excercises
     */
    private function calculateExcercisesReports($excercises)
    {
        $excerciseReports = array();

        foreach ($excercises as $excerciseInstance)
        {
            $key = $excerciseInstance->getName();
            if (!isset($excerciseReports[$key]))
            {
                $excerciseReports[$key] = new ExcerciseReport();
                $excerciseReports[$key]->setName($key)
                        ->setSeries(1)
                        ->setWeight($excerciseInstance->getWeight())
                        ->setResult($excerciseInstance->getResult())
                        ->setBaseExcercise($excerciseInstance->getBaseExcercise());
            }
            else
            {
                $excerciseReports[$key]->setSeries($excerciseReports[$key]->getSeries() + 1);

                $currentResult = $excerciseReports[$key]->getResult();
                $newResult     = $excerciseInstance->getResult();
                $finalResult   = $currentResult < $newResult ? $newResult : $currentResult;

                $excerciseReports[$key]->setResult($finalResult);
            }
        }

        return $excerciseReports;
    }

}
