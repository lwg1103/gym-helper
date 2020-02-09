<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Training;
use App\Security\TrainingVoter;
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
        return $this->render(
                        'training/index.twig',
                        [
                            'trainings' => $this->getUser()->getTrainings()
                        ]
        );
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request)
    {
        $training = new Training();
        $form     = $this->createForm(TrainingType::class, $training);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $training = $form->getData();
            $training->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('training_index');
        }

        return $this->render(
                        'training/new_edit.twig',
                        [
                            'form' => $form->createView()
                        ]
        );
    }

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(int $id)
    {
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);
        
        $this->denyAccessUnlessGranted(TrainingVoter::EDIT, $training);

        if ($training)
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($training);
            $entityManager->flush();
        }

        return $this->redirectToRoute("training_index");
    }

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Request $request, int $id)
    {
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);

        if (!$training)
        {
            throw $this->createNotFoundException('The training does not exist');
        }
        
        $this->denyAccessUnlessGranted(TrainingVoter::EDIT, $training);

        $form = $this->createForm(TrainingType::class, $training);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $training = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('training_index');
        }

        return $this->render(
                        'training/new_edit.twig',
                        [
                            'form' => $form->createView()
                        ]
        );
    }

}
