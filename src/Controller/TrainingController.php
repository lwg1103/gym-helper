<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Training;

class TrainingController extends AbstractController
{
    /**
     * @Route("/training", name="training_index")
     */
    public function index()
    {
        $trainings = $this->getDoctrine()->getRepository(Training::class)->findAll();
        return $this->render('training/index.twig',
                [
                    'trainings' => $trainings
                ]);
    }
}
