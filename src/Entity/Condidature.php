<?php

namespace App\Entity;

use App\Repository\CondidatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CondidatureRepository::class)]
class Condidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"Veuillez saisir le nom de condidat.")]
    #[ORM\Column(length: 255)]
    private ?string $nom_c = null;

    #[Assert\NotBlank(message:"Veuillez saisir l'email de condidat.")]
    #[Assert\Email(message:"Veuillez verifier la format de l'email.")]
    #[ORM\Column(length: 255)]
    private ?string $email_c = null;

    #[Assert\NotBlank(message:"Veuillez mettre le Cv de condidat.")]
    #[ORM\Column(length: 255)]
    private ?string $cv_c = null;
    
    #[Assert\NotBlank(message:"Veuillez saisir la lettre de motivation.")]
    #[ORM\Column(length: 255)]
    private ?string $lettre_mo = null;

    #[ORM\ManyToOne(inversedBy: 'condidatures')]
    private ?Offer $offer = null;

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
