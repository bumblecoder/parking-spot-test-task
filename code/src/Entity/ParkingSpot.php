<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Enum\ParkingSpotType;
use App\Repository\ParkingSpotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParkingSpotRepository::class)]
#[ORM\Table(name: '`parking_spot`')]
class ParkingSpot
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', unique: true)]
    #[ORM\JoinColumn(options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(name: 'type', length: 255, enumType: ParkingSpotType::class)]
    #[Assert\NotBlank]
    private ?ParkingSpotType $type = null;

    #[ORM\Column(name: 'is_occupied', type: 'boolean')]
    private ?bool $isOccupied = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?ParkingSpotType
    {
        return $this->type;
    }

    public function setType(ParkingSpotType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isOccupied(): ?bool
    {
        return $this->isOccupied;
    }

    public function setIsOccupied(bool $isOccupied): self
    {
        $this->isOccupied = $isOccupied;

        return $this;
    }
}
