<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Training;
use App\Form\TrainingType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/training", name="training_")
 */
class TrainingController extends AbstractController
{
    /**
     * @Route("/", name="index")
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
     * @Route("/new", name="new")
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
    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(int $id)
    {
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);
        
        if ($training)
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($training);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute("training_index");
    }
}
