<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\Vehicle;
use App\Enum\VehicleType;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Vehicle>
 */
final class VehicleFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Vehicle::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        return [
            'type' => self::faker()->randomElement(VehicleType::cases()),
        ];
    }
}
