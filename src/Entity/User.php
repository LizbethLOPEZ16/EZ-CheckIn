<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, \JsonSerializable, \Serializable
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Person", cascade={"persist", "remove"})
     */
    private $person;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ParentStudent", mappedBy="parent")
     */
    private $parentStudents;

    public function __construct()
    {
        $this->parentStudents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles()
    {
        return $this->roles;
        // return [
        //     'ROLE_USER'
        // ];
    }
    public function getSalt()
    { }
    public function eraseCredentials()
    { }

    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "email" => $this->email,
            "account" => $this->account,
            "person" => $this->person,
            "roles" => $this->roles,
        ];
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
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
            $parentStudent->setParent($this);
        }

        return $this;
    }

    public function removeParentStudent(ParentStudent $parentStudent): self
    {
        if ($this->parentStudents->contains($parentStudent)) {
            $this->parentStudents->removeElement($parentStudent);
            // set the owning side to null (unless already changed)
            if ($parentStudent->getParent() === $this) {
                $parentStudent->setParent(null);
            }
        }

        return $this;
    }
}
