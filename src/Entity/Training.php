<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainingRepository")
 */
class Training
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Excercise", mappedBy="training", orphanRemoval=true)
     */
    private $excercises;
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\TrainingInstance", mappedBy="baseTraining", cascade={"persist", "remove"})
     */
    private $trainingInstance;

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
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Excercise[]
     */
    public function getExcercises(): Collection
    {
        return $this->excercises;
    }

    public function addExcercise(Excercise $excercise): self
    {
        if (!$this->excercises->contains($excercise))
        {
            $this->excercises[] = $excercise;
            $excercise->setTraining($this);
        }

        return $this;
    }

    public function removeExcercise(Excercise $excercise): self
    {
        if ($this->excercises->contains($excercise))
        {
            $this->excercises->removeElement($excercise);
            // set the owning side to null (unless already changed)
            if ($excercise->getTraining() === $this)
            {
                $excercise->setTraining(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getTrainingInstance(): ?TrainingInstance
    {
        return $this->trainingInstance;
    }

    public function setTrainingInstance(?TrainingInstance $trainingInstance): self
    {
        $this->trainingInstance = $trainingInstance;

        if ($trainingInstance)
        {
            // set (or unset) the owning side of the relation if necessary
            $newBaseTraining = $this;
            if ($trainingInstance->getBaseTraining() !== $newBaseTraining)
            {
                $trainingInstance->setBaseTraining($newBaseTraining);
            }
        }

        return $this;
    }
    
    public function isStarted()
    {
        return null != $this->trainingInstance;
    }

}
