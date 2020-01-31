<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExcerciseReportRepository")
 */
class ExcerciseReport
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
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $series;

    /**
     * @ORM\Column(type="integer")
     */
    private $result;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Excercise")
     * @ORM\JoinColumn(nullable=false)
     */
    private $baseExcercise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TrainingReport", inversedBy="excerciseReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trainingReport;

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

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getSeries(): ?int
    {
        return $this->series;
    }

    public function setSeries(int $series): self
    {
        $this->series = $series;

        return $this;
    }

    public function getResult(): ?int
    {
        return $this->result;
    }

    public function setResult(int $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getBaseExcercise(): ?Excercise
    {
        return $this->baseExcercise;
    }

    public function setBaseExcercise(?Excercise $baseExcercise): self
    {
        $this->baseExcercise = $baseExcercise;

        return $this;
    }

    public function getTrainingReport(): ?TrainingReport
    {
        return $this->trainingReport;
    }

    public function setTrainingReport(?TrainingReport $trainingReport): self
    {
        $this->trainingReport = $trainingReport;

        return $this;
    }
}
