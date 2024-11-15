<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AddressRepository;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['livraison', 'facturation'], message: 'Invalid address type.')]
    private ?string $adr_type = null;

    #[ORM\Column(length: 100)]
    private ?string $adr_city = null;

    #[ORM\Column(length: 255)]
    private ?string $adr_address = null;

    #[ORM\Column]
    private ?int $adr_cp = null;

    #[ORM\Column]
    private ?bool $is_default = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdrType(): ?string
    {
        return $this->adr_type;
    }

    public function setAdrType(string $adr_type): static
    {
        $this->adr_type = $adr_type;

        return $this;
    }

    public function getAdrCity(): ?string
    {
        return $this->adr_city;
    }

    public function setAdrCity(string $adr_city): static
    {
        $this->adr_city = $adr_city;

        return $this;
    }

    public function getAdrAddress(): ?string
    {
        return $this->adr_address;
    }

    public function setAdrAddress(string $adr_address): static
    {
        $this->adr_address = $adr_address;

        return $this;
    }

    public function getAdrCp(): ?int
    {
        return $this->adr_cp;
    }

    public function setAdrCp(int $adr_cp): static
    {
        $this->adr_cp = $adr_cp;

        return $this;
    }

    public function isDefault(): ?bool
    {
        return $this->is_default;
    }

    public function setDefault(bool $is_default): static
    {
        $this->is_default = $is_default;

        return $this;
    }
}
