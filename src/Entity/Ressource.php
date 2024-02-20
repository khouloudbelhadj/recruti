<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre_b = null;

    #[ORM\Column(length: 255)]
    private ?string $type_b = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_publica_b = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie_resso_b = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    private ?biblio $biblio = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreB(): ?string
    {
        return $this->titre_b;
    }

    public function setTitreB(string $titre_b): static
    {
        $this->titre_b = $titre_b;

        return $this;
    }

    public function getTypeB(): ?string
    {
        return $this->type_b;
    }

    public function setTypeB(string $type_b): static
    {
        $this->type_b = $type_b;

        return $this;
    }

    public function getDatePublicaB(): ?\DateTimeInterface
    {
        return $this->date_publica_b;
    }

    public function setDatePublicaB(\DateTimeInterface $date_publica_b): static
    {
        $this->date_publica_b = $date_publica_b;

        return $this;
    }

    public function getCategorieRessoB(): ?string
    {
        return $this->categorie_resso_b;
    }

    public function setCategorieRessoB(string $categorie_resso_b): static
    {
        $this->categorie_resso_b = $categorie_resso_b;

        return $this;
    }

    public function getBiblio(): ?biblio
    {
        return $this->biblio;
    }

    public function setBiblio(?biblio $biblio): static
    {
        $this->biblio = $biblio;

        return $this;
    }
}
