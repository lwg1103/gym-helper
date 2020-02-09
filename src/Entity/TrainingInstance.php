<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainingInstanceRepository")
 */
class TrainingInstance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Training", inversedBy="trainingInstance")
     */
    private $baseTraining;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExcerciseInstance", mappedBy="trainingInstance", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $excercises;

    public function __construct()
    {
        $this->excercises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->baseTraining->getName();
    }

    public function getBaseTraining(): ?Training
    {
        return $this->baseTraining;
    }
    
    public function getBaseTrainingId(): ?int
    {
        return $this->baseTraining->getId();
    }

    public function setBaseTraining(?Training $baseTraining): self
    {
        $this->baseTraining = $baseTraining;

        return $this;
    }

    /**
     * @return Collection|ExcerciseInstance[]
     */
    public function getExcercises(): Collection
    {
        return $this->excercises;
    }

    public function addExcercise(ExcerciseInstance $excercise): self
    {
        if (!$this->excercises->contains($excercise)) {
            $this->excercises[] = $excercise;
            $excercise->setTrainingInstance($this);
        }

        return $this;
    }

    public function removeExcercise(ExcerciseInstance $excercise): self
    {
        if ($this->excercises->contains($excercise)) {
            $this->excercises->removeElement($excercise);
            // set the owning side to null (unless already changed)
            if ($excercise->getTrainingInstance() === $this) {
                $excercise->setTrainingInstance(null);
            }
        }

        return $this;
    }
    
    public function getOwner()
    {
        $this->baseTraining->getUser();
    }
          
}
