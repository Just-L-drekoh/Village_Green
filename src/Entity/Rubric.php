<?php

namespace App\Entity;

use App\Repository\RubricRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RubricRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_RUBRIC_SLUG', fields: ['rub_slug'])]

class Rubric
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $rub_label = null;

    #[ORM\Column(length: 100)]
    private ?string $rub_slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $rub_desc = null;

    #[ORM\Column(length: 255)]
    private ?string $rub_img = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRubLabel(): ?string
    {
        return $this->rub_label;
    }

    public function setRubLabel(string $rub_label): static
    {
        $this->rub_label = $rub_label;

        return $this;
    }

    public function getRubSlug(): ?string
    {
        return $this->rub_slug;
    }

    public function setRubSlug(string $rub_slug): static
    {
        $this->rub_slug = $rub_slug;

        return $this;
    }

    public function getRubDesc(): ?string
    {
        return $this->rub_desc;
    }

    public function setRubDesc(string $rub_desc): static
    {
        $this->rub_desc = $rub_desc;

        return $this;
    }

    public function getRubImg(): ?string
    {
        return $this->rub_img;
    }

    public function setRubImg(string $rub_img): static
    {
        $this->rub_img = $rub_img;

        return $this;
    }
}
