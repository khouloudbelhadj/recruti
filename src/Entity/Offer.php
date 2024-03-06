<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre_o = null;

    #[ORM\Column(length: 255)]
    private ?string $description_o = null;

    #[ORM\Column(length: 255)]
    private ?string $type_o = null;

    #[ORM\Column(length: 255)]
    private ?string $localisation_o = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_o = null;

    #[ORM\Column(length: 255)]
    private ?string $dure_o = null;

    #[ORM\Column(length: 255)]
    private ?string $salarie_o = null;

    #[ORM\OneToMany(targetEntity: Condidature::class, mappedBy: 'offer')]
    private Collection $condidatures;

    #[ORM\OneToMany(targetEntity: Rendezvous::class, mappedBy: 'ManyToOne', orphanRemoval: true)]
    private Collection $rendezvouses;

    public function __construct()
    {
        $this->condidatures = new ArrayCollection();
        $this->rendezvouses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreO(): ?string
    {
        return $this->titre_o;
    }

    public function setTitreO(string $titre_o): static
    {
        $this->titre_o = $titre_o;

        return $this;
    }

    public function getDescriptionO(): ?string
    {
        return $this->description_o;
    }

    public function setDescriptionO(string $description_o): static
    {
        $this->description_o = $description_o;

        return $this;
    }

    public function getTypeO(): ?string
    {
        return $this->type_o;
    }

    public function setTypeO(string $type_o): static
    {
        $this->type_o = $type_o;

        return $this;
    }

    public function getLocalisationO(): ?string
    {
        return $this->localisation_o;
    }

    public function setLocalisationO(string $localisation_o): static
    {
        $this->localisation_o = $localisation_o;

        return $this;
    }

    public function getDateO(): ?\DateTimeInterface
    {
        return $this->date_o;
    }

    public function setDateO(\DateTimeInterface $date_o): static
    {
        $this->date_o = $date_o;

        return $this;
    }

    public function getDureO(): ?string
    {
        return $this->dure_o;
    }

    public function setDureO(string $dure_o): static
    {
        $this->dure_o = $dure_o;

        return $this;
    }

    public function getSalarieO(): ?string
    {
        return $this->salarie_o;
    }

    public function setSalarieO(string $salarie_o): static
    {
        $this->salarie_o = $salarie_o;

        return $this;
    }

    /**
     * @return Collection<int, Condidature>
     */
    public function getCondidatures(): Collection
    {
        return $this->condidatures;
    }

    public function addCondidature(Condidature $condidature): static
    {
        if (!$this->condidatures->contains($condidature)) {
            $this->condidatures->add($condidature);
            $condidature->setOffer($this);
        }

        return $this;
    }

    public function removeCondidature(Condidature $condidature): static
    {
        if ($this->condidatures->removeElement($condidature)) {
            // set the owning side to null (unless already changed)
            if ($condidature->getOffer() === $this) {
                $condidature->setOffer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rendezvous>
     */
    public function getRendezvouses(): Collection
    {
        return $this->rendezvouses;
    }

    public function addRendezvouse(Rendezvous $rendezvouse): static
    {
        if (!$this->rendezvouses->contains($rendezvouse)) {
            $this->rendezvouses->add($rendezvouse);
            $rendezvouse->setManyToOne($this);
        }

        return $this;
    }

    public function removeRendezvouse(Rendezvous $rendezvouse): static
    {
        if ($this->rendezvouses->removeElement($rendezvouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezvouse->getManyToOne() === $this) {
                $rendezvouse->setManyToOne(null);
            }
        }

        return $this;
    }
  
}
