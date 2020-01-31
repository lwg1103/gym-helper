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

        $trainingReport->setBaseTraining($trainingInstance->getBaseTraining())
                ->setName($trainingInstance->getBaseTraining()->getName());

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
                $excerciseReports[$key] = $this->createNewExcerciseReport($excerciseInstance);
            }
            else
            {
                $this->updateExcerciseReport($excerciseReports[$key], $excerciseInstance);
            }
        }

        return $excerciseReports;
    }

    private function createNewExcerciseReport($excerciseInstance)
    {
        $report = new ExcerciseReport();

        $report->setName($excerciseInstance->getName())
                ->setSeries(1)
                ->setWeight($excerciseInstance->getWeight())
                ->setResult($excerciseInstance->getResult())
                ->setBaseExcercise($excerciseInstance->getBaseExcercise());

        return $report;
    }

    private function updateExcerciseReport($excerciseReport, $excerciseInstance)
    {
        $excerciseReport->setSeries($excerciseReport->getSeries() + 1);

        $currentResult = $excerciseReport->getResult();
        $newResult     = $excerciseInstance->getResult();
        $finalResult   = $currentResult < $newResult ? $newResult : $currentResult;

        $excerciseReport->setResult($finalResult);
    }

}
