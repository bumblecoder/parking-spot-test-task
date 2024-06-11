<?php

declare(strict_types = 1);

namespace App\Enum;

enum ParkingSpotType: string
{
    case REGULAR = 'regular';
    case MOTORCYCLE = 'motorcycle';
    case VAN = 'van';

    public static function fromVehicleType(string $vehicleType): ?self
    {
        return match (strtolower($vehicleType)) {
            'car', 'regular' => self::REGULAR,
            'motorcycle' => self::MOTORCYCLE,
            'van' => self::VAN,
            default => null,
        };
    }
}
