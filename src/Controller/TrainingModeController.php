<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Training;
use App\Entity\ExcerciseInstance;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Service\TrainingManager\ITrainingInstanceManager;
use App\Service\TrainingManager\IExcerciseInstanceManager;
use App\Service\TrainingManager\Exception\TrainingAlreadyStartedException;
use App\Service\TrainingManager\Exception\TrainingNotStartedException;

/**
 * @Route("/training-mode", name="training_mode_")
 */
class TrainingModeController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $trainings = $this->getDoctrine()->getRepository(Training::class)->findAll();

        return $this->render(
                        'training-mode/index.twig',
                        [
                            'trainings' => $trainings
                        ]
        );
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(int $id)
    {
        $training = $this->getTraining($id)->getTrainingInstance();

        return $this->render(
                        'training-mode/show.twig',
                        [
                            'training' => $training
                        ]
        );
    }

    /**
     * @Route("/{id}/start", name="start")
     */
    public function start(int $id, ITrainingInstanceManager $trainingInstanceManager)
    {
        try
        {
            $trainingInstanceManager->startTraining($this->getTraining($id));

            return $this->redirectToRoute("training_mode_show", ['id' => $id]);
        }
        catch (TrainingAlreadyStartedException $ex)
        {
            throw new HttpException(400, 'The training is already started');
        }
    }

    /**
     * @Route("/{id}/restart", name="restart")
     */
    public function restart(int $id, ITrainingInstanceManager $trainingInstanceManager)
    {
        try
        {
            $trainingInstanceManager->restartTraining($this->getTraining($id));

            return $this->redirectToRoute("training_mode_show", ['id' => $id]);
        }
        catch (TrainingNotStartedException $ex)
        {
            throw new HttpException(400, 'The training is not started');
        }
    }

    /**
     * @Route("/{id}/finish", name="finish")
     */
    public function finish(int $id, ITrainingInstanceManager $trainingInstanceManager)
    {
        try
        {
            $training = $this->getTraining($id);
            $trainingInstanceManager->finishTraining($training);

            return $this->redirectToRoute(
                    "training_report_show", 
                    ['id' => $this->getLastTrainingReportForTraining($training)->getId()]
                    );
        }
        catch (TrainingNotStartedException $ex)
        {
            throw new HttpException(400, 'The training is not started');
        }
    }
    
    /**
     * @Route("/excercise/{id}/ok", name="ok_excercise")
     */
    public function setExcerciseOk(int $id, IExcerciseInstanceManager $excerciseManager)
    {
        $excerciseInstance = $this->getExcerciseInstance($id);
        $excerciseManager->markAsOk($excerciseInstance);        
        
        return $this->redirectToRoute("training_mode_show", ['id' => $excerciseInstance->getTrainingInstance()->getBaseTraining()->getId()]);
    }
    
    /**
     * @Route("/excercise/{id}/easy", name="easy_excercise")
     */
    public function setExcerciseEasy(int $id, IExcerciseInstanceManager $excerciseManager)
    {
        $excerciseInstance = $this->getExcerciseInstance($id);
        $excerciseManager->markAsTooEasy($excerciseInstance);        
        
        return $this->redirectToRoute("training_mode_show", ['id' => $excerciseInstance->getTrainingInstance()->getBaseTraining()->getId()]);
    }
    
    /**
     * @Route("/excercise/{id}/hard", name="hard_excercise")
     */
    public function setExcerciseHard(int $id, IExcerciseInstanceManager $excerciseManager)
    {
        $excerciseInstance = $this->getExcerciseInstance($id);
        $excerciseManager->markAsTooHard($excerciseInstance);        
        
        return $this->redirectToRoute("training_mode_show", ['id' => $excerciseInstance->getTrainingInstance()->getBaseTraining()->getId()]);
    }

    private function getTraining($id)
    {
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);

        if (!$training)
        {
            throw $this->createNotFoundException('The training does not exist');
        }

        return $training;
    }

    private function getLastTrainingReportForTraining(Training $training)
    {
        $trainingReport = $this->getDoctrine()
                ->getRepository(\App\Entity\TrainingReport::class)
                ->findLastReportForTraining($training);

        return $trainingReport;
    }

    private function getExcerciseInstance($id)
    {
        $excerciseInstance = $this->getDoctrine()->getRepository(ExcerciseInstance::class)->find($id);

        if (!$excerciseInstance)
        {
            throw $this->createNotFoundException('The excercise instance does not exist');
        }

        return $excerciseInstance;
    }

}
