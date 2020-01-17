<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Training;
use App\Form\TrainingType;
use Symfony\Component\HttpFoundation\Request;

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
    
    /**
     * @Route("/training/new", name="training_new")
     */
    public function new(Request $request)
    {
        $training = new Training();
        $form = $this->createForm(TrainingType::class, $training);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $training = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('training_index');
        }
        
        return $this->render('training/new.twig',
                [
                    'form' => $form->createView()
                ]);
    }
}
