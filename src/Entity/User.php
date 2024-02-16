<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['login'], message: 'There is already an account with this login')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $login = null;
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function setInscriptions(Collection $inscriptions): static
    {
        $this->inscriptions = $inscriptions;
        return $this;
    }
    #[ORM\ManyToMany(targetEntity: Lecon::class)]
    private ?Collection $inscriptions;


    #[ORM\OneToMany(mappedBy: 'professeur', targetEntity: Lecon::class)]
    private Collection $lecons;

    public function getLecons(): Collection
    {
        return $this->lecons;
    }

    public function setLecons(Collection $lecons): void
    {
        $this->lecons = $lecons;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }
    #[ORM\Column]
    private array $roles = [];

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function addInscriptions(Lecon $inscrits): static
    {
        if (!$this->inscriptions->contains($inscrits)) {
            $this->inscriptions->add($inscrits);
        }

        return $this;
    }
    public function removeInscription(Lecon $inscrits): static
    {
        if ($this->inscriptions->contains($inscrits)) {
            $this->inscriptions->removeElement($inscrits);
        }

        return $this;
    }
    public function addLecons(Lecon $lecon): static
    {
        if (!$this->lecons->contains($lecon)) {
            $this->lecons->add($lecon);
            $lecon->setProfesseur($this);
        }

        return $this;
    }
}
