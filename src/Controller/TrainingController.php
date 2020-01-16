<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrainingController extends AbstractController
{
    /**
     * @Route("/training", name="training_index")
     */
    public function index()
    {
        $number = random_int(0, 100);

        return $this->render('training/index.twig');
    }
}
