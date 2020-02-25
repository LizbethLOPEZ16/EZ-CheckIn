<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClienteRepository")
 */
class Cliente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $administrador;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdministrador(): ?string
    {
        return $this->administrador;
    }

    public function setAdministrador(?string $administrador): self
    {
        $this->administrador = $administrador;

        return $this;
    }
}
