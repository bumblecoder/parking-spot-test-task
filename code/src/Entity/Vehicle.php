<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Enum\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
#[UniqueEntity('parkingSpot')]
class Vehicle
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: 'string', enumType: VehicleType::class)]
    #[Assert\NotBlank]
    private ?VehicleType $type = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getType(): ?VehicleType
    {
        return $this->type;
    }

    public function setType(VehicleType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
