<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_ORD_REF', fields: ['ord_ref'])]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $ord_date = null;

    #[ORM\Column(length: 50)]
    private ?string $ord_ref = null;

    #[ORM\Column(length: 50)]
    private ?string $ord_status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $payment_date = null;

    #[ORM\Column(length: 255)]
    private ?string $archive_doc = null;

    #[ORM\Column(length: 50)]
    private ?string $payment_method = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $invoice_date = null;

    #[ORM\Column(length: 50)]
    private ?string $payment_status = null;

    #[ORM\Column(length: 50)]
    private ?string $archive_type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdDate(): ?\DateTimeImmutable
    {
        return $this->ord_date;
    }

    public function setOrdDate(\DateTimeImmutable $ord_date): static
    {
        $this->ord_date = $ord_date;

        return $this;
    }

    public function getOrdRef(): ?string
    {
        return $this->ord_ref;
    }

    public function setOrdRef(string $ord_ref): static
    {
        $this->ord_ref = $ord_ref;

        return $this;
    }

    public function getOrdStatus(): ?string
    {
        return $this->ord_status;
    }

    public function setOrdStatus(string $ord_status): static
    {
        $this->ord_status = $ord_status;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPaymentDate(\DateTimeInterface $payment_date): static
    {
        $this->payment_date = $payment_date;

        return $this;
    }

    public function getArchiveDoc(): ?string
    {
        return $this->archive_doc;
    }

    public function setArchiveDoc(string $archive_doc): static
    {
        $this->archive_doc = $archive_doc;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): static
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoice_date;
    }

    public function setInvoiceDate(\DateTimeInterface $invoice_date): static
    {
        $this->invoice_date = $invoice_date;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->payment_status;
    }

    public function setPaymentStatus(string $payment_status): static
    {
        $this->payment_status = $payment_status;

        return $this;
    }

    public function getArchiveType(): ?string
    {
        return $this->archive_type;
    }

    public function setArchiveType(string $archive_type): static
    {
        $this->archive_type = $archive_type;

        return $this;
    }
}
