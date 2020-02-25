<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParentStudentRepository")
 */
class ParentStudent implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="parentStudents")
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Student", inversedBy="parentStudents")
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?User
    {
        return $this->parent;
    }

    public function setParent(?User $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "parent" => $this->parent,
            "student" => $this->student
        ];
    }
}
