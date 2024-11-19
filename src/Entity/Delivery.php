<?php

namespace App\Entity;

use App\Repository\DeliveryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryRepository::class)]
class Delivery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $deli_date = null;

    #[ORM\Column(length: 100)]
    private ?string $shipping_note = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliDate(): ?\DateTimeInterface
    {
        return $this->deli_date;
    }

    public function setDeliDate(\DateTimeInterface $deli_date): static
    {
        $this->deli_date = $deli_date;

        return $this;
    }

    public function getShippingNote(): ?string
    {
        return $this->shipping_note;
    }

    public function setShippingNote(string $shipping_note): static
    {
        $this->shipping_note = $shipping_note;

        return $this;
    }
}
