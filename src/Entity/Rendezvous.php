<?php

namespace App\Entity;

use App\Repository\RendezvousRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RendezvousRepository::class)]
class Rendezvous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "Appointment date cannot be empty , Do not choose a pre-existing date  ")]
    private ?\DateTimeInterface $dateRendez = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Appointment time cannot be empty")]
    private ?string $heureRendez = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The meeting place cannot be empty")]
    private ?string $lieu = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Condi's email cannot be empty")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    private ?string $emailCondi = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Representative email cannot be empty")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    private ?string $emailRepresen = null;

    #[ORM\ManyToOne(targetEntity: Calendrier::class, inversedBy: 'rendezvouses')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Calendrier $calendrier = null;

    #[ORM\ManyToOne(targetEntity: Offer::class, inversedBy: 'rendezvouses')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Offer $offer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRendez(): ?\DateTimeInterface
    {
        return $this->dateRendez;
    }

    public function setDateRendez(?\DateTimeInterface $dateRendez): self
    {
        $this->dateRendez = $dateRendez;
        return $this;
    }

    public function getHeureRendez(): ?string
    {
        return $this->heureRendez;
    }

    public function setHeureRendez(?string $heureRendez): self
    {
        $this->heureRendez = $heureRendez;
        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;
        return $this;
    }

    public function getEmailCondi(): ?string
    {
        return $this->emailCondi;
    }

    public function setEmailCondi(?string $emailCondi): self
    {
        $this->emailCondi = $emailCondi;
        return $this;
    }

    public function getEmailRepresen(): ?string
    {
        return $this->emailRepresen;
    }

    public function setEmailRepresen(?string $emailRepresen): self
    {
        $this->emailRepresen = $emailRepresen;
        return $this;
    }

    public function getCalendrier(): ?Calendrier
    {
        return $this->calendrier;
    }

    public function setCalendrier(?Calendrier $calendrier): self
    {
        $this->calendrier = $calendrier;
        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;
        return $this;
    }

    
}
