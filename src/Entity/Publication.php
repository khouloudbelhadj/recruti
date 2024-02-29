<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Media;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]

class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creationpub = null;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'publication',orphanRemoval:true, cascade: ["persist"])]
    private Collection $media;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'publication')]
    private Collection $commentaires;

    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'publication')]
    private Collection $likes;

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCreationpub(): ?\DateTimeInterface
    {
        return $this->date_creationpub;
    }

    public function setDateCreationpub(\DateTimeInterface $date_creationpub): static
    {
        $this->date_creationpub = $date_creationpub;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setPublication($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): static
    {
        if ($this->medium->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getPublication() === $this) {
                $medium->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setPublication($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPublication() === $this) {
                $commentaire->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setPublication($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPublication() === $this) {
                $like->setPublication(null);
            }
        }

        return $this;
    }
}
