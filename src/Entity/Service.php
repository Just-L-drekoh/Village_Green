<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['apres-vente', 'commercial', 'comptabilite', 'equipe'], message: 'Invalid service type.')]
    private ?string $serv_type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServType(): ?string
    {
        return $this->serv_type;
    }

    public function setServType(string $serv_type): static
    {
        $this->serv_type = $serv_type;

        return $this;
    }
}
