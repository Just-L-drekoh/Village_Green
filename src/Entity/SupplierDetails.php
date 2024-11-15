<?php

namespace App\Entity;

use App\Repository\SupplierDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplierDetailsRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_SPL_SIRET', fields: ['spl_siret'])]

class SupplierDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $spl_siret = null;

    #[ORM\Column(length: 20)]
    private ?string $spl_type = null;

    #[ORM\Column]
    private ?bool $spl_status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSplSiret(): ?string
    {
        return $this->spl_siret;
    }

    public function setSplSiret(string $spl_siret): static
    {
        $this->spl_siret = $spl_siret;

        return $this;
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
}
