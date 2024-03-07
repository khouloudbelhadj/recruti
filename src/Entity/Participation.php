<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null ;

    #[Assert\NotBlank(message:"Please choose your role")]
    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[Assert\NotBlank(message:"Please choose the status of your participation.")]
    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[Assert\NotBlank(message:"Please enter the feedback.")]
    #[ORM\Column(length: 255)]
    private ?string $feedback = null;

    #[Assert\NotBlank(message:"Please enter your name.")]
    #[ORM\Column(length: 255)]
    private ?string $nom_participant = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    private ?Event $event = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    public function setFeedback(string $feedback): static
    {
        $this->feedback = $feedback;

        return $this;
    }

    public function getNomParticipant(): ?string
    {
        return $this->nom_participant;
    }

    public function setNomParticipant(string $nom_participant): static
    {
        $this->nom_participant = $nom_participant;

        return $this;
    }

    public function getEvent(): ?event
    {
        return $this->event;
    }

    public function setEvent(?event $event): static
    {
        $this->event = $event;

        return $this;
    }


    public function getparticipationDataForQrCode(): string
    {
        $data = "Id: {$this->id}, Role: {$this->role}, Status: {$this->statut}, Feedback: {$this->feedback}, Participant Name: {$this->nom_participant}, event Name: {$this->event}";

        

        return $data;
    }
}
