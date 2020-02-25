<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Student implements \JsonSerializable
{
    // Unmapped for file uploading
    protected $file;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $school_name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $schedule;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $grade;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $id_aleatorio;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ParentStudent", mappedBy="student")
     */
    private $parentStudents;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $time_in;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $time_out;

    public function __construct()
    {
        $this->parentStudents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSchoolName(): ?string
    {
        return $this->school_name;
    }

    public function setSchoolName(?string $school_name): self
    {
        $this->school_name = $school_name;

        return $this;
    }

    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(?string $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    // Unmaped file setters and getters
    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    public function getIdAleatorio(): ?string
    {
        return $this->id_aleatorio;
    }

    public function setIdAleatorio(?string $id_aleatorio): self
    {
        $this->id_aleatorio = $id_aleatorio;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function convertUploadedCsvToArray(LifecycleEventArgs $event)
    {
        if (null === $this->getFile()) {
            return;
        }

        $entityManager = $event->getEntityManager();
        $repository = $entityManager->getRepository(get_class($this));

        $file = $this->getFile();

        $lines = explode("\n", file_get_contents($file));

        $headers = str_getcsv(array_shift($lines));
        $students = array();
        foreach ($lines as $line) {
            $row = array();
            foreach (str_getcsv($line) as $key => $field)
                // Somewhat dirty solution to remove unknown characters in headers
                $row[preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $headers[$key])] = $field;
            $row = array_filter($row);
            $students[] = $row;
        }
        // Empty the 
        $this->setFile(null);

        $repository->createStudents(array_filter($students));
    }

    /**
     * @return Collection|ParentStudent[]
     */
    public function getParentStudents(): Collection
    {
        return $this->parentStudents;
    }

    public function addParentStudent(ParentStudent $parentStudent): self
    {
        if (!$this->parentStudents->contains($parentStudent)) {
            $this->parentStudents[] = $parentStudent;
            $parentStudent->setStudent($this);
        }

        return $this;
    }

    public function removeParentStudent(ParentStudent $parentStudent): self
    {
        if ($this->parentStudents->contains($parentStudent)) {
            $this->parentStudents->removeElement($parentStudent);
            // set the owning side to null (unless already changed)
            if ($parentStudent->getStudent() === $this) {
                $parentStudent->setStudent(null);
            }
        }

        return $this;
    }

    public function getTimeIn(): ?\DateTimeInterface
    {
        return $this->time_in;
    }

    public function setTimeIn(?\DateTimeInterface $time_in): self
    {
        $this->time_in = $time_in;

        return $this;
    }

    public function getTimeOut(): ?\DateTimeInterface
    {
        return $this->time_out;
    }

    public function setTimeOut(?\DateTimeInterface $time_out): self
    {
        $this->time_out = $time_out;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "schoolName" => $this->school_name,
            "schedule" => $this->schedule,
            "grade" => $this->grade,
            "barcode" => $this->id_aleatorio,
            "parentStudents" => $this->parentStudents,
            "timeIn" => $this->time_in,
            "timeOut" => $this->time_out,
        ];
    }
}
