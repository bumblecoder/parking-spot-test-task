<?php

declare(strict_types = 1);

namespace App\Enum;

enum VehicleType: string
{
    case MOTORCYCLE = 'motorcycle';
    case CAR = 'car';
    case VAN = 'van';
}
