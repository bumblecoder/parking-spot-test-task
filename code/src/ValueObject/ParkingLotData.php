<?php

declare(strict_types = 1);

namespace App\ValueObject;

use App\Entity\ParkingSpot;

class ParkingLotData
{
    public int $totalSpots;

    /** @var array|ParkingSpot[] */
    public array $availableSpots = [];

    public function getTotalSpots(): int
    {
        return $this->totalSpots;
    }

    public function getAvailableSpots(): array
    {
        return $this->availableSpots;
    }

    public function setTotalSpots(int $totalSpots): self
    {
        $this->totalSpots = $totalSpots;

        return $this;
    }

    public function setAvailableSpots(array $availableSpots): self
    {
        $this->availableSpots = $availableSpots;

        return $this;
    }
}
