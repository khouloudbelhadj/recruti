<?php

namespace App\Entity;

use App\Repository\RendezvousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezvousRepository::class)]
class Rendezvous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_rendez = null;

    #[ORM\Column(length: 255)]
    private ?string $heure_rendez = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255)]
    private ?string $email_condi = null;

    #[ORM\Column(length: 255)]
    private ?string $email_represen = null;

    #[ORM\ManyToOne(inversedBy: 'rendezvouses')]
    private ?calendrier $calendrier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRendez(): ?\DateTimeInterface
    {
        return $this->date_rendez;
    }

    public function setDateRendez(\DateTimeInterface $date_rendez): static
    {
        $this->date_rendez = $date_rendez;

        return $this;
    }

    public function getHeureRendez(): ?string
    {
        return $this->heure_rendez;
    }

    public function setHeureRendez(string $heure_rendez): static
    {
        $this->heure_rendez = $heure_rendez;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEmailCondi(): ?string
    {
        return $this->email_condi;
    }

    public function setEmailCondi(string $email_condi): static
    {
        $this->email_condi = $email_condi;

        return $this;
    }

    public function getEmailRepresen(): ?string
    {
        return $this->email_represen;
    }

    public function setEmailRepresen(string $email_represen): static
    {
        $this->email_represen = $email_represen;

        return $this;
    }

    public function getCalendrier(): ?calendrier
    {
        return $this->calendrier;
    }

    public function setCalendrier(?calendrier $calendrier): static
    {
        $this->calendrier = $calendrier;

        return $this;
    }
}
