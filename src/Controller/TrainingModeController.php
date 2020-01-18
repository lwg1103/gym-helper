<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Training;

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
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);

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
            $this->redirectToRoute("training_mod_index");
        }
    }

}
