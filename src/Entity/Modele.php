<?php

namespace App\Entity;

use App\Repository\ModeleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModeleRepository::class)]
class Modele
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $agence = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signes_particuliers = null;

    #[ORM\Column]
    private ?float $taille = null;

    #[ORM\Column]
    private ?float $taille_hanches = null;

    #[ORM\Column(nullable: true)]
    private ?float $tour_de_poitrine = null;

    #[ORM\Column]
    private ?float $pointure = null;

    #[ORM\Column]
    private ?float $poids = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur_yeux = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur_cheveux = null;

    #[ORM\Column(length: 255)]
    private ?string $type_ethnique = null;

    #[ORM\OneToOne(inversedBy: 'modele', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgence(): ?string
    {
        return $this->agence;
    }

    public function setAgence(string $agence): static
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

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(float $taille): static
    {
        $this->taille = $taille;
        return $this;
    }

    public function getTailleHanches(): ?float
    {
        return $this->taille_hanches;
    }

    public function setTailleHanches(float $taille_hanches): static
    {
        $this->taille_hanches = $taille_hanches;
        return $this;
    }

    public function getTourDePoitrine(): ?float
    {
        return $this->tour_de_poitrine;
    }

    public function setTourDePoitrine(?float $tour_de_poitrine): static
    {
        $this->tour_de_poitrine = $tour_de_poitrine;
        return $this;
    }

    public function getPointure(): ?float
    {
        return $this->pointure;
    }

    public function setPointure(float $pointure): static
    {
        $this->pointure = $pointure;
        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): static
    {
        $this->poids = $poids;
        return $this;
    }

    public function getCouleurYeux(): ?string
    {
        return $this->couleur_yeux;
    }

    public function setCouleurYeux(string $couleur_yeux): static
    {
        $this->couleur_yeux = $couleur_yeux;
        return $this;
    }

    public function getCouleurCheveux(): ?string
    {
        return $this->couleur_cheveux;
    }

    public function setCouleurCheveux(string $couleur_cheveux): static
    {
        $this->couleur_cheveux = $couleur_cheveux;
        return $this;
    }

    public function getTypeEthnique(): ?string
    {
        return $this->type_ethnique;
    }

    public function setTypeEthnique(string $type_ethnique): static
    {
        $this->type_ethnique = $type_ethnique;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }
}
