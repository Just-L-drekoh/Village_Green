<?php

namespace App\Entity;

use App\Repository\RubricRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'rubrics')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $rubrics;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'rubric')]
    private Collection $products;

    public function __construct()
    {
        $this->rubrics = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getRubrics(): Collection
    {
        return $this->rubrics;
    }

    public function addRubric(self $rubric): static
    {
        if (!$this->rubrics->contains($rubric)) {
            $this->rubrics->add($rubric);
            $rubric->setParent($this);
        }

        return $this;
    }

    public function removeRubric(self $rubric): static
    {
        if ($this->rubrics->removeElement($rubric)) {
            // set the owning side to null (unless already changed)
            if ($rubric->getParent() === $this) {
                $rubric->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setRubric($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getRubric() === $this) {
                $product->setRubric(null);
            }
        }

        return $this;
    }
}
