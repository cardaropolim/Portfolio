<?php

namespace App\Entity;

use App\Repository\TarifsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TarifsRepository::class)]
class Tarifs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ 'prestations' ne peut pas être vide.")]
    private ?string $prestations = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'tarifs')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrestations(): ?string
    {
        return $this->prestations;
    }

    public function setPrestations(string $prestations): static
    {
        $this->prestations = $prestations;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
