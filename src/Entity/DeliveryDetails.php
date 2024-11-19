<?php

namespace App\Entity;

use App\Repository\DeliveryDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryDetailsRepository::class)]
class DeliveryDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $shipped_qty = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShippedQty(): ?int
    {
        return $this->shipped_qty;
    }

    public function setShippedQty(int $shipped_qty): static
    {
        $this->shipped_qty = $shipped_qty;

        return $this;
    }
}
