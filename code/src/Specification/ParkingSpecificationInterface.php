<?php

declare(strict_types = 1);

namespace App\Specification;

use App\Entity\ParkingSpot;
use App\Enum\VehicleType;

interface ParkingSpecificationInterface
{
    public function isSatisfiedBy(VehicleType $vehicleType, ParkingSpot $parkingSpot): bool;

    public function getMessage(): string;
}
