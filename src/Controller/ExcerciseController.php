<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Excercise;
use App\Form\ExcerciseType;

/**
 * @Route("/excercise", name="excercise_")
 */
class ExcerciseController extends AbstractController
{

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request)
    {
        $excercise = new Excercise();
        $form      = $this->createForm(ExcerciseType::class, $excercise);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $excercise = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($excercise);
            $entityManager->flush();

            return $this->redirectToRoute('training_index');
        }

        return $this->render(
                        'excercise/new_edit.twig',
                        [
                            'form' => $form->createView()
                        ]
        );
    }

}
