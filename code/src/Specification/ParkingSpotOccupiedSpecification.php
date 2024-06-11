<?php

declare(strict_types = 1);

namespace App\Specification;

use App\Entity\ParkingSpot;
use App\Enum\VehicleType;

class ParkingSpotOccupiedSpecification implements ParkingSpecificationInterface
{
    public function isSatisfiedBy(VehicleType $vehicleType, ParkingSpot $parkingSpot): bool
    {
        return false === $parkingSpot->isOccupied();
    }

    public function getMessage(): string
    {
        return 'Parking spot is already occupied.';
    }
}
