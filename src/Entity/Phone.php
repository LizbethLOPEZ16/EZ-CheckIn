<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 */
class Phone implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $country_code;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phone_number;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(?string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "countryCode" => $this->country_code,
            "phoneNumber" => $this->phone_number,
        ];
    }
}
