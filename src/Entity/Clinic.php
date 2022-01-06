<?php

namespace App\Entity;

use App\Repository\ClinicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClinicRepository::class)]
class Clinic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['clinic:all', 'pet:no_owner'])]
    private $id;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['clinic:all', 'pet:no_owner'])]
    private $name;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['clinic:all', 'pet:no_owner'])]
    private $location;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'boolean')]
    #[Groups(['clinic:all'])]
    private $avaliable;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'clinics')]
    #[Groups(['clinic:all'])]
    private $users;

    #[ORM\OneToMany(mappedBy: 'clinic', targetEntity: Pet::class)]
    #[Groups(['clinic:all'])]
    private $pets;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->pets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getAvaliable(): ?bool
    {
        return $this->avaliable;
    }

    public function setAvaliable(bool $avaliable): self
    {
        $this->avaliable = $avaliable;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

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
            $pet->setClinic($this);
        }

        return $this;
    }

    public function removePet(Pet $pet): self
    {
        if ($this->pets->removeElement($pet)) {
            // set the owning side to null (unless already changed)
            if ($pet->getClinic() === $this) {
                $pet->setClinic(null);
            }
        }

        return $this;
    }

    #[Pure] public function __toString(): string
    {
        return $this->getName();
    }


}
