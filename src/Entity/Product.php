<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_PRODUCT_REF', fields: ['prod_ref'])]
#[ORM\UniqueConstraint(name: 'UNIQ_PRODUCT_SLUG', fields: ['prod_slug'])]


class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $prod_label = null;

    #[ORM\Column(length: 100)]
    private ?string $prod_slug = null;

    #[ORM\Column(length: 50)]
    private ?string $prod_ref = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $prod_desc = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prod_price = null;

    #[ORM\Column]
    private ?int $prod_stock = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?SupplierDetails $supplier = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'product')]
    private Collection $img;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rubric $rubric = null;

    public function __construct()
    {
        $this->img = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProdLabel(): ?string
    {
        return $this->prod_label;
    }

    public function setProdLabel(string $prod_label): static
    {
        $this->prod_label = $prod_label;

        return $this;
    }

    public function getProdSlug(): ?string
    {
        return $this->prod_slug;
    }

    public function setProdSlug(string $prod_slug): static
    {
        $this->prod_slug = $prod_slug;

        return $this;
    }

    public function getProdRef(): ?string
    {
        return $this->prod_ref;
    }

    public function setProdRef(string $prod_ref): static
    {
        $this->prod_ref = $prod_ref;

        return $this;
    }

    public function getProdDesc(): ?string
    {
        return $this->prod_desc;
    }

    public function setProdDesc(string $prod_desc): static
    {
        $this->prod_desc = $prod_desc;

        return $this;
    }

    public function getProdPrice(): ?string
    {
        return $this->prod_price;
    }

    public function setProdPrice(string $prod_price): static
    {
        $this->prod_price = $prod_price;

        return $this;
    }

    public function getProdStock(): ?int
    {
        return $this->prod_stock;
    }

    public function setProdStock(int $prod_stock): static
    {
        $this->prod_stock = $prod_stock;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getSupplier(): ?SupplierDetails
    {
        return $this->supplier;
    }

    public function setSupplier(?SupplierDetails $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImg(): Collection
    {
        return $this->img;
    }

    public function addImg(Image $img): static
    {
        if (!$this->img->contains($img)) {
            $this->img->add($img);
            $img->setProduct($this);
        }

        return $this;
    }

    public function removeImg(Image $img): static
    {
        if ($this->img->removeElement($img)) {
            // set the owning side to null (unless already changed)
            if ($img->getProduct() === $this) {
                $img->setProduct(null);
            }
        }

        return $this;
    }

    public function getRubric(): ?Rubric
    {
        return $this->rubric;
    }

    public function setRubric(?Rubric $rubric): static
    {
        $this->rubric = $rubric;

        return $this;
    }
}
