<?php

namespace App\Entity;

use App\Repository\SupplierDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplierDetailsRepository::class)]


class SupplierDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $spl_type = null;

    #[ORM\Column]
    private ?bool $spl_status = null;

    #[ORM\ManyToOne(inversedBy: 'supplier')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSplType(): ?string
    {
        return $this->spl_type;
    }

    public function setSplType(string $spl_type): static
    {
        $this->spl_type = $spl_type;

        return $this;
    }

    public function isSplStatus(): ?bool
    {
        return $this->spl_status;
    }

    public function setSplStatus(bool $spl_status): static
    {
        $this->spl_status = $spl_status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
