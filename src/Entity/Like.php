<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    private ?int $userID = null;

    private ?int $publicationID = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creationLike = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Publication $publication = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreationLike(): ?\DateTimeInterface
    {
        return $this->date_creationLike;
    }

    public function setDateCreationLike(\DateTimeInterface $date_creationLike): static
    {
        $this->date_creationLike = $date_creationLike;

        return $this;
    }

    public function getPublication(): ?Publication
    {
        return $this->publication;
    }

    public function setPublication(?Publication $publication): static
    {
        $this->publication = $publication;

        return $this;
    }

    public function setPublicationID(?int $publication): static
    {
        $this->publicationID = $publication;

        return $this;
    }

    public function setUserID(?int $userID): static
    {
        $this->userID = $userID;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
