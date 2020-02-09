<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainingReportRepository")
 */
class TrainingReport
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
     * @ORM\OneToMany(targetEntity="App\Entity\ExcerciseReport", mappedBy="trainingReport", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $excerciseReports;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Training")
     */
    private $baseTraining;

    public function __construct()
    {
        $this->excerciseReports = new ArrayCollection();
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
     * @return Collection|ExcerciseReport[]
     */
    public function getExcerciseReports(): Collection
    {
        return $this->excerciseReports;
    }

    public function addExcerciseReport(ExcerciseReport $excerciseReport): self
    {
        if (!$this->excerciseReports->contains($excerciseReport)) {
            $this->excerciseReports[] = $excerciseReport;
            $excerciseReport->setTrainingReport($this);
        }

        return $this;
    }

    public function removeExcerciseReport(ExcerciseReport $excerciseReport): self
    {
        if ($this->excerciseReports->contains($excerciseReport)) {
            $this->excerciseReports->removeElement($excerciseReport);
            // set the owning side to null (unless already changed)
            if ($excerciseReport->getTrainingReport() === $this) {
                $excerciseReport->setTrainingReport(null);
            }
        }

        return $this;
    }

    public function getBaseTraining(): ?Training
    {
        return $this->baseTraining;
    }

    public function setBaseTraining(?Training $baseTraining): self
    {
        $this->baseTraining = $baseTraining;

        return $this;
    }
    
    public function getOwner()
    {
        return $this->baseTraining->getUser();
    }
}
