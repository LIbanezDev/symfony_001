<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['pet:no_owner', 'clinic:all'])]
    private $id;


    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['pet:no_owner', 'clinic:all'])]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[Ignore]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5)]
    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 200)]
    #[Groups(['pet:no_owner', 'clinic:all'])]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $profile_image;

    #[ORM\Column(type: 'date')]
    #[Groups('pet:no_owner')]
    private $born_date;

    #[MaxDepth(1)]
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Pet::class)]
    private $pets;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated;

    #[ORM\ManyToMany(targetEntity: Clinic::class, mappedBy: 'users')]
    private $clinics;

    public function __construct()
    {
        $this->pets = new ArrayCollection();
        $this->clinics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profile_image;
    }

    public function setProfileImage(?string $profile_image): self
    {
        $this->profile_image = $profile_image;

        return $this;
    }

    public function getBornDate(): ?\DateTimeInterface
    {
        return $this->born_date;
    }

    public function setBornDate(\DateTimeInterface $born_date): self
    {
        $this->born_date = $born_date;

        return $this;
    }

    /**
     * @return Collection|Pet[]
     */
    public function getPets(): Collection
    {
        return $this->pets;
    }

    public function addPet(Pet $pet): self
    {
        if (!$this->pets->contains($pet)) {
            $this->pets[] = $pet;
            $pet->setOwner($this);
        }

        return $this;
    }

    public function removePet(Pet $pet): self
    {
        if ($this->pets->removeElement($pet)) {
            // set the owning side to null (unless already changed)
            if ($pet->getOwner() === $this) {
                $pet->setOwner(null);
            }
        }

        return $this;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return Collection|Clinic[]
     */
    public function getClinics(): Collection
    {
        return $this->clinics;
    }

    public function addClinic(Clinic $clinic): self
    {
        if (!$this->clinics->contains($clinic)) {
            $this->clinics[] = $clinic;
            $clinic->addUser($this);
        }

        return $this;
    }

    public function removeClinic(Clinic $clinic): self
    {
        if ($this->clinics->removeElement($clinic)) {
            $clinic->removeUser($this);
        }

        return $this;
    }
}
