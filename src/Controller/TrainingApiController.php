<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/training", name="api_training_")
 */
class TrainingApiController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $data = [];
        
        foreach ($this->getUser()->getTrainings() as $training) {
            $data[] = [
                "id" => $training->getId(),
                "name" => $training->getName(),
            ];
        }
        
        return $this->json($data);
    }  
}
