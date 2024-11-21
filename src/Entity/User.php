<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_REF', fields: ['userRef'])]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_SIRET', fields: ['userSiret'])]
#[UniqueEntity(fields: ['email'], message: "L'adresse mail est deja utilise. Utiliser une autre adresse mail.")]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $userRef = null;

    #[ORM\Column(length: 100)]
    private ?string $userFirstname = null;

    #[ORM\Column(length: 100)]
    private ?string $userLastname = null;

    #[ORM\Column(length: 20)]
    private ?string $userPhone = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $coef = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $userSiret = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $user_last_conn = null;

    /**
     * @var Collection<int, Address>
     */
    #[ORM\OneToMany(targetEntity: Address::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $address;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Service $service = null;

    /**
     * @var Collection<int, SupplierDetails>
     */
    #[ORM\OneToMany(targetEntity: SupplierDetails::class, mappedBy: 'user')]
    private Collection $supplier;

    #[ORM\Column]
    private ?bool $is_verified = null;

 
   

    public function __construct()
    {
        $this->address = new ArrayCollection();
        $this->supplier = new ArrayCollection();
        
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserRef(): ?string
    {
        return $this->userRef;
    }

    public function setUserRef(string $userRef): static
    {
        $this->userRef = $userRef;

        return $this;
    }

    public function getUserFirstname(): ?string
    {
        return $this->userFirstname;
    }

    public function setUserFirstname(string $userFirstname): static
    {
        $this->userFirstname = $userFirstname;

        return $this;
    }

    public function getUserLastname(): ?string
    {
        return $this->userLastname;
    }

    public function setUserLastname(string $userLastname): static
    {
        $this->userLastname = $userLastname;

        return $this;
    }

    public function getUserPhone(): ?string
    {
        return $this->userPhone;
    }

    public function setUserPhone(string $userPhone): static
    {
        $this->userPhone = $userPhone;

        return $this;
    }

    public function getCoef(): ?string
    {
        return $this->coef;
    }

    public function setCoef(string $coef): static
    {
        $this->coef = $coef;

        return $this;
    }

    public function getUserSiret(): ?string
    {
        return $this->userSiret;
    }

    public function setUserSiret(?string $userSiret): static
    {
        $this->userSiret = $userSiret;

        return $this;
    }

    public function getUserLastConn(): ?\DateTimeImmutable
    {
        return $this->user_last_conn;
    }

    public function setUserLastConn(\DateTimeImmutable $user_last_conn): static
    {
        $this->user_last_conn = $user_last_conn;

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(Address $address): static
    {
        if (!$this->address->contains($address)) {
            $this->address->add($address);
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): static
    {
        if ($this->address->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return Collection<int, SupplierDetails>
     */
    public function getSupplier(): Collection
    {
        return $this->supplier;
    }

    public function addSupplier(SupplierDetails $supplier): static
    {
        if (!$this->supplier->contains($supplier)) {
            $this->supplier->add($supplier);
            $supplier->setUser($this);
        }

        return $this;
    }

    public function removeSupplier(SupplierDetails $supplier): static
    {
        if ($this->supplier->removeElement($supplier)) {
            // set the owning side to null (unless already changed)
            if ($supplier->getUser() === $this) {
                $supplier->setUser(null);
            }
        }

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setVerified(bool $is_verified): static
    {
        $this->is_verified = $is_verified;

        return $this;
    }



   
}
