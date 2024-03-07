<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]


class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

  #[ORM\Column(length: 255)]
  /**
    
     * @Assert\NotBlank(message = "ce champs est obligatoire")
     * @Assert\Length(max =20, maxMessage = "username ne peut pas dépasser {{ limit }} caractères")
   */
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    /**
    
     * @Assert\NotBlank(message = "ce champs est obligatoire")
     * @Assert\Length(max =8 , min =8, maxMessage = "le cin a {{ limit }} chiffres")
   */
    private ?string $cin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_birth = null;

    #[ORM\Column(length: 255)]
    private ?string $email_user = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
   
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getDateBirth(): ?\DateTimeInterface
    {
        return $this->date_birth;
    }

    public function setDateBirth(\DateTimeInterface $date_birth): static
    {
        $this->date_birth = $date_birth;

        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->email_user;
    }

    public function setEmailUser(string $email_user): static
    {
        $this->email_user = $email_user;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }
    /**
     * @see  PasswordAuthenticatedUserInterface
     */

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }
    public function getRoles(): ?array
    {
        return [$this->role];
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
    
    public function eraseCredentials(): void
    {
        // Erase any sensitive data on this user object
    }
    public function getSalt(): ?string
    {
        return $this->username; 
    }
    public function getUserIdentifier(): string
    {
        return (string) $this->email_user;
    }
}
