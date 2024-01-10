<?php

namespace App\Entity;

use App\Repository\AcquisitionUnitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use App\Domain\AcquisitionUnitState;
#[ORM\Entity(repositoryClass: AcquisitionUnitRepository::class)]
class AcquisitionUnit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\Column(length: 7)]
    #[Assert\NotBlank(message: 'Le nom du SA ne peut pas être vide')]
    #[Assert\Length(
        max: 3,
        maxMessage: 'Le nom du SA ne peut pas contenir plus de 3 caractères'
    )]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new UniqueEntity([
            'fields' => ['name'],
            'message' => 'Ce SA existe déjà.',
        ]));
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
