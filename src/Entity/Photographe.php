<?php

namespace App\Entity;

use App\Repository\PhotographeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotographeRepository::class)]
class Photographe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
     private ?int $id = null; 
     
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reseaux_sociaux = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $agence = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signes_particuliers = null;

    #[ORM\OneToOne(inversedBy: 'photographe', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;



    public function getId(): ?int
    {
        return $this->id;
    }

     public function gxetReseauxSociaux(): ?string
     {
         return $this->reseaux_sociaux;
     }
     public function setReseauxSociaux(?string $reseaux_sociaux): static
     {
         $this->reseaux_sociaux = $reseaux_sociaux;
         return $this;
     }

    public function getAgence(): ?string
    {
        return $this->agence;
    }

    public function setAgence(?string $agence): static
    {
        $this->agence = $agence;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSignesParticuliers(): ?string
    {
        return $this->signes_particuliers;
    }

    public function setSignesParticuliers(?string $signes_particuliers): static
    {
        $this->signes_particuliers = $signes_particuliers;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
   
    public function setUser(?User $User): static
    {
        $this->user = $User;
        return $this;
    }

}

