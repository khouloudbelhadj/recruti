<?php

namespace App\Entity;

use App\Repository\CondidatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CondidatureRepository::class)]
class Condidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_c = null;

    #[ORM\Column(length: 255)]
    private ?string $email_c = null;

    #[ORM\Column(length: 255)]
    private ?string $cv_c = null;

    #[ORM\Column(length: 255)]
    private ?string $lettre_mo = null;

    #[ORM\ManyToOne(inversedBy: 'condidatures')]
    private ?offer $offer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomC(): ?string
    {
        return $this->nom_c;
    }

    public function setNomC(string $nom_c): static
    {
        $this->nom_c = $nom_c;

        return $this;
    }

    public function getEmailC(): ?string
    {
        return $this->email_c;
    }

    public function setEmailC(string $email_c): static
    {
        $this->email_c = $email_c;

        return $this;
    }

    public function getCvC(): ?string
    {
        return $this->cv_c;
    }

    public function setCvC(string $cv_c): static
    {
        $this->cv_c = $cv_c;

        return $this;
    }

    public function getLettreMo(): ?string
    {
        return $this->lettre_mo;
    }

    public function setLettreMo(string $lettre_mo): static
    {
        $this->lettre_mo = $lettre_mo;

        return $this;
    }

    public function getOffer(): ?offer
    {
        return $this->offer;
    }

    public function setOffer(?offer $offer): static
    {
        $this->offer = $offer;

        return $this;
    }
}
