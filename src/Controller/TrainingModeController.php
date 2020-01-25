<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Training;
use App\Entity\TrainingInstance;
use App\Service\InstanceGenerator\ITrainingInstanceGenerator;
use App\Entity\ExcerciseInstance;
use App\Model\ExcerciseInstanceResult;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Service\TrainingManager\ITrainingInstanceManager;
use App\Service\TrainingManager\Exception\TrainingAlreadyStartedException;

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
            $trainingInstance = $trainingInstanceManager->startTraining($this->getTraining($id));

            return $this->redirectToRoute("training_mode_show", ['id' => $trainingInstance->getId()]);
        }
        catch (TrainingAlreadyStartedException $ex)
        {
            throw new HttpException(400, 'The training is already started');
        }
    }

    /**
     * @Route("/{id}/restart", name="restart")
     */
    public function restart(int $id, ITrainingInstanceGenerator $generator)
    {
        $trainingInstance = $this->getTrainingInstance($id);

        $this->getDoctrine()->getManager()->remove($trainingInstance);
        $this->getDoctrine()->getManager()->flush();

        $newTrainingInstance = $this->generateTrainingInstance($generator, $trainingInstance->getBaseTraining());

        return $this->redirectToRoute("training_mode_show", ['id' => $newTrainingInstance->getId()]);
    }

    /**
     * @Route("/excercise/{id}/done", name="done_excercise")
     */
    public function done(int $id)
    {
        $excerciseInstance = $this->getExcerciseInstance($id);

        $excerciseInstance->setResult(ExcerciseInstanceResult::Done);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute("training_mode_show", ['id' => $excerciseInstance->getTrainingInstance()->getId()]);
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

    private function getTrainingInstance($id)
    {
        $trainingInstance = $this->getDoctrine()->getRepository(TrainingInstance::class)->find($id);

        if (!$trainingInstance)
        {
            throw $this->createNotFoundException('The training instance does not exist');
        }

        return $trainingInstance;
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

    private function generateTrainingInstance($generator, $training)
    {
        $trainingInstance = $generator->generate($training);

        $this->getDoctrine()->getManager()->persist($trainingInstance);
        $this->getDoctrine()->getManager()->flush();

        return $trainingInstance;
    }

}
