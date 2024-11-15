<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $prod_img = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProdImg(): ?string
    {
        return $this->prod_img;
    }

    public function setProdImg(string $prod_img): static
    {
        $this->prod_img = $prod_img;

        return $this;
    }
}
