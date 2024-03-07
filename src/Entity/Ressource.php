<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;


#[UniqueEntity(fields: ['titre_b'], message: 'This resource title is already in use. Please choose another one.')]
#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"Please enter the ressource's title.")]
    #[ORM\Column(length: 255)]
    private ?string $titre_b = null;

    #[Assert\NotBlank(message:"Please enter the ressource's type.")]    
    #[ORM\Column(length: 255)]
    private ?string $type_b = null;

    #[Assert\NotBlank(message:"Please enter the ressource's date.")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_publica_b = null;

    #[Assert\NotBlank(message:"Please enter the ressource's Field.")]
    #[ORM\Column(length: 255)]
    private ?string $categorie_resso_b = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    private ?Biblio $biblio = null;

    //new attributs
    #[Assert\NotBlank(message: "Please enter the resource's description.")]
    #[ORM\Column(length: 255)]
    private ?string $description_b = null;

    #[Assert\NotBlank(message:"Please choose an image for the resource.")]
    #[ORM\Column(length: 255)]
    private ?string $image_b_ressource = null;

    //

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreB(): ?string
    {
        return $this->titre_b;
    }

    public function setTitreB(string $titre_b): static
    {
        $this->titre_b = $titre_b;

        return $this;
    }

    public function getTypeB(): ?string
    {
        return $this->type_b;
    }

    public function setTypeB(string $type_b): static
    {
        $this->type_b = $type_b;

        return $this;
    }

    public function getDatePublicaB(): ?\DateTimeInterface
    {
        return $this->date_publica_b;
    }

    public function setDatePublicaB(\DateTimeInterface $date_publica_b): static
    {
        $this->date_publica_b = $date_publica_b;

        return $this;
    }

    public function getCategorieRessoB(): ?string
    {
        return $this->categorie_resso_b;
    }

    public function setCategorieRessoB(string $categorie_resso_b): static
    {
        $this->categorie_resso_b = $categorie_resso_b;

        return $this;
    }

    public function getBiblio(): ?biblio
    {
        return $this->biblio;
    }

    public function setBiblio(?biblio $biblio): static
    {
        $this->biblio = $biblio;

        return $this;
    }

    // Getters and setters for the new attributes

    public function getDescriptionB(): ?string
    {
        return $this->description_b;
    }

    public function setDescriptionB(?string $description_b): static
    {
        $this->description_b = $description_b;

        return $this;
    }

    public function getImageBRessource(): ?string
    {
        return $this->image_b_ressource;
    }

    public function setImageBRessource(?string $image_b_ressource): static
    {
        $this->image_b_ressource = $image_b_ressource;

        return $this;
    }

}
