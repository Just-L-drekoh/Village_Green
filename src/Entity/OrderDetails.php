<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
class OrderDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $det_qty = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $det_price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetQty(): ?int
    {
        return $this->det_qty;
    }

    public function setDetQty(int $det_qty): static
    {
        $this->det_qty = $det_qty;

        return $this;
    }

    public function getDetPrice(): ?string
    {
        return $this->det_price;
    }

    public function setDetPrice(string $det_price): static
    {
        $this->det_price = $det_price;

        return $this;
    }
}
