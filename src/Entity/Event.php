<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_e = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_e = null;

    #[ORM\Column(length: 255)]
    private ?string $heure_e = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu_e = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image_e = null;

    #[ORM\Column(length: 255)]
    private ?string $theme_e = null;

    #[ORM\Column(length: 255)]
    private ?string $cantact_e = null;

    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'event')]
    private Collection $participations;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomE(): ?string
    {
        return $this->nom_e;
    }

    public function setNomE(string $nom_e): static
    {
        $this->nom_e = $nom_e;

        return $this;
    }

    public function getDateE(): ?\DateTimeInterface
    {
        return $this->date_e;
    }

    public function setDateE(\DateTimeInterface $date_e): static
    {
        $this->date_e = $date_e;

        return $this;
    }

    public function getHeureE(): ?string
    {
        return $this->heure_e;
    }

    public function setHeureE(string $heure_e): static
    {
        $this->heure_e = $heure_e;

        return $this;
    }

    public function getLieuE(): ?string
    {
        return $this->lieu_e;
    }

    public function setLieuE(string $lieu_e): static
    {
        $this->lieu_e = $lieu_e;

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

    public function getImageE(): ?string
    {
        return $this->image_e;
    }

    public function setImageE(string $image_e): static
    {
        $this->image_e = $image_e;

        return $this;
    }

    public function getThemeE(): ?string
    {
        return $this->theme_e;
    }

    public function setThemeE(string $theme_e): static
    {
        $this->theme_e = $theme_e;

        return $this;
    }

    public function getCantactE(): ?string
    {
        return $this->cantact_e;
    }

    public function setCantactE(string $cantact_e): static
    {
        $this->cantact_e = $cantact_e;

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setEvent($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getEvent() === $this) {
                $participation->setEvent(null);
            }
        }

        return $this;
    }
}
