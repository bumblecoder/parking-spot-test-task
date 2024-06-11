<?php

declare(strict_types = 1);

namespace App\Specification;

use App\Entity\ParkingSpot;
use App\Enum\ParkingSpotType;
use App\Enum\VehicleType;

class VehicleTypeMatchingSpecification implements ParkingSpecificationInterface
{
    public function isSatisfiedBy(VehicleType $vehicleType, ParkingSpot $parkingSpot): bool
    {
        $parkingSpotType = ParkingSpotType::fromVehicleType($vehicleType->value);

        return null !== $parkingSpotType && $parkingSpotType === $parkingSpot->getType();
    }

    public function getMessage(): string
    {
        return 'This vehicle type does not match parking spot.';
    }
}
