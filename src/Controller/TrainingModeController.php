<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Training;
use App\Entity\TrainingInstance;
use App\Service\TrainingInstanceGenerator;
use App\Entity\ExcerciseInstance;
use App\Model\ExcerciseInstanceStatus;

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
        $training = $this->getTrainingInstance($id);

        if ($training)
        {
            return $this->render(
                            'training-mode/show.twig',
                            [
                                'training' => $training
                            ]
            );
        }
        else
        {
            return $this->redirectToRoute("training_mode_index");
        }
    }

    /**
     * @Route("/{id}/start", name="start")
     */
    public function start(int $id, TrainingInstanceGenerator $generator)
    {
        $training         = $this->getTraining($id);
        
        if ($training->getTrainingInstance())
        {
            return $this->redirectToRoute("training_mode_show", ['id' => $training->getTrainingInstance()->getId()]);
        }
        
        $trainingInstance = $generator->generate($training);
        
        $this->getDoctrine()->getManager()->persist($trainingInstance);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute("training_mode_show", ['id' => $trainingInstance->getId()]);
    }
    
    /**
     * 
     * @Route("/{id}/done", name="done")
     */
    public function done(int $id)
    {
        $excerciseInstance = $this->getExcerciseInstance($id);
        
        if (!$excerciseInstance)
        {
            throw $this->createNotFoundException('The excercise does not exist');
        }
        
        $excerciseInstance->setResult(ExcerciseInstanceStatus::Done);
        $this->getDoctrine()->getManager()->flush();
        
        return $this->redirectToRoute("training_mode_show", ['id' => $excerciseInstance->getTrainingInstance()->getId()]);
    }

    private function getTraining($id)
    {
        return $this->getDoctrine()->getRepository(Training::class)->find($id);
    }

    private function getTrainingInstance($id)
    {
        return $this->getDoctrine()->getRepository(TrainingInstance::class)->find($id);
    }

    private function getExcerciseInstance($id)
    {
        return $this->getDoctrine()->getRepository(ExcerciseInstance::class)->find($id);
    }

}
