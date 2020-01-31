<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\TrainingReport;

/**
 * @Route("/training-report", name="training_report_")
 */
class TrainingReportController extends AbstractController
{

    /**
     * @Route("/{id}", name="show")
     */
    public function show(int $id)
    {
        $trainingReport = $this->getTrainingReport($id);

        return $this->render(
                        'training-report/show.twig',
                        [
                            'report' => $trainingReport
                        ]
        );
    }

    private function getTrainingReport($id)
    {
        $report = $this->getDoctrine()->getRepository(TrainingReport::class)->find($id);

        if (!$report)
        {
            throw $this->createNotFoundException('The training report does not exist');
        }

        return $report;
    }
    
}
