<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExcerciseInstanceRepository")
 */
class ExcerciseInstance
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
     * @ORM\Column(type="integer")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $repeats;

    /**
     * @ORM\Column(type="integer")
     */
    private $breakTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $result;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TrainingInstance", inversedBy="excercises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trainingInstance;

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

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getRepeats(): ?int
    {
        return $this->repeats;
    }

    public function setRepeats(int $repeats): self
    {
        $this->repeats = $repeats;

        return $this;
    }

    public function getBreakTime(): ?int
    {
        return $this->breakTime;
    }

    public function setBreakTime(int $breakTime): self
    {
        $this->breakTime = $breakTime;

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

    public function getTrainingInstance(): ?TrainingInstance
    {
        return $this->trainingInstance;
    }

    public function setTrainingInstance(?TrainingInstance $trainingInstance): self
    {
        $this->trainingInstance = $trainingInstance;

        return $this;
    }
}
