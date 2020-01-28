<?php

namespace App\Service\TrainingManager;

use App\Entity\ExcerciseInstance;
use App\Model\ExcerciseInstanceResult;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineExcerciseInstanceManager implements IExcerciseInstanceManager
{
    /**
     * @var EntityManagerInterface 
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function markAsOk(ExcerciseInstance $excerciseInstance)
    {
        $this->setResult($excerciseInstance, ExcerciseInstanceResult::Ok);
    }

    public function markAsTooEasy(ExcerciseInstance $excerciseInstance)
    {
        $this->setResult($excerciseInstance, ExcerciseInstanceResult::TooEasy);
    }

    public function markAsTooHard(ExcerciseInstance $excerciseInstance)
    {
        $this->setResult($excerciseInstance, ExcerciseInstanceResult::TooHard);
    }

    private function setResult(ExcerciseInstance $excerciseInstance, $result)
    {
        $excerciseInstance->setResult($result);
        $this->entityManager->flush();
    }

}
