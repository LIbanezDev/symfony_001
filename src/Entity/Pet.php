<?php

namespace App\Entity;

use App\Repository\PetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PetRepository::class)]
class Pet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['pet:no_owner', 'clinic:all'])]
    private $id;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['pet:no_owner', 'clinic:all'])]
    private $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('pet:no_owner')]
    private $owner;

    #[ORM\ManyToOne(targetEntity: Clinic::class, inversedBy: 'pets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('pet:no_owner')]
    private $clinic;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $profile_image;

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

    public function getOwner(): ?user
    {
        return $this->owner;
    }

    public function setOwner(?user $owner): self
    {
        $this->owner = $owner;

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

    public function getClinic(): ?Clinic
    {
        return $this->clinic;
    }

    public function setClinic(?Clinic $clinic): self
    {
        $this->clinic = $clinic;

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
}
