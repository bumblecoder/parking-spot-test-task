<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\ParkingSpot;
use App\Enum\ParkingSpotType;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @extends PersistentProxyObjectFactory<ParkingSpot>
 */
final class ParkingSpotFactory extends PersistentProxyObjectFactory
{
    use ResetDatabase;

    public static function class(): string
    {
        return ParkingSpot::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        return [
            'isOccupied' => false,
            'type' => self::faker()->randomElement(ParkingSpotType::cases()),
        ];
    }
}
