<?php

namespace App\Entity;

use App\Repository\BiblioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BiblioRepository::class)]
class Biblio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_b = null;

    #[ORM\Column(length: 255)]
    private ?string $domaine_b = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation_b = null;

    #[ORM\OneToMany(targetEntity: Ressource::class, mappedBy: 'biblio')]
    private Collection $ressources;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomB(): ?string
    {
        return $this->nom_b;
    }

    public function setNomB(string $nom_b): static
    {
        $this->nom_b = $nom_b;

        return $this;
    }

    public function getDomaineB(): ?string
    {
        return $this->domaine_b;
    }

    public function setDomaineB(string $domaine_b): static
    {
        $this->domaine_b = $domaine_b;

        return $this;
    }

    public function getDateCreationB(): ?\DateTimeInterface
    {
        return $this->date_creation_b;
    }

    public function setDateCreationB(\DateTimeInterface $date_creation_b): static
    {
        $this->date_creation_b = $date_creation_b;

        return $this;
    }

    /**
     * @return Collection<int, Ressource>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): static
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
            $ressource->setBiblio($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): static
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getBiblio() === $this) {
                $ressource->setBiblio(null);
            }
        }

        return $this;
    }
}
