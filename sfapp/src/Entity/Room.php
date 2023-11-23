<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 4)]
    #[Assert\NotBlank(message: 'Le nom de la salle ne peut pas être vide')]
    #[Assert\Length(
        max: 4,
        maxMessage: 'Le nom de la salle ne peut pas contenir plus de 4 caractères'
    )]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(message: 'Le numéro d\'étage ne peut pas être négatif')]
    #[Assert\NotBlank(message: 'Le numéro d\'étage ne peut pas être vide')]
    private ?int $floor = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(message: 'La capacité ne peut pas être négative')]
    #[Assert\NotBlank(message: 'La capacité ne peut pas être vide')]
    private ?int $capacity = null;

    #[ORM\Column]
    private ?bool $hasComputers = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'La surface ne peut pas être vide')]
    #[Assert\Positive(message: 'La surface ne peut pas être négative ou null')]
    private ?int $area = null;

    #[ORM\Column(length: 25)]
    private ?string $exposure = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le nombre de fenêtres ne peut pas être vide')]
    #[Assert\PositiveOrZero(message: 'Le nombre de fenêtre ne peut pas être négative')]
    private ?int $nbWindows = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?AcquisitionUnit $SA = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): static
    {
        $this->floor = $floor;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function isHasComputers(): ?bool
    {
        return $this->hasComputers;
    }

    public function setHasComputers(bool $hasComputers): static
    {
        $this->hasComputers = $hasComputers;

        return $this;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(int $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getExposure(): ?string
    {
        return $this->exposure;
    }

    public function setExposure(string $exposure): static
    {
        $this->exposure = $exposure;

        return $this;
    }

    public function getNbWindows(): ?int
    {
        return $this->nbWindows;
    }

    public function setNbWindows(int $nbWindows): static
    {
        $this->nbWindows = $nbWindows;

        return $this;
    }

    public function getSA(): ?AcquisitionUnit
    {
        return $this->SA;
    }

    public function setSA(?AcquisitionUnit $SA): static
    {
        $this->SA = $SA;

        return $this;
    }
}
