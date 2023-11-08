<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 4)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $floor = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\Column]
    private ?bool $hasComputers = null;

    #[ORM\Column]
    private ?int $area = null;

    #[ORM\Column(length: 25)]
    private ?string $exposure = null;

    #[ORM\Column]
    private ?int $nbWindows = null;

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
}
