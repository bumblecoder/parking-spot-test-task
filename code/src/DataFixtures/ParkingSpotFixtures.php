<?php

declare(strict_types = 1);

namespace App\DataFixtures;

use App\Factory\ParkingSpotFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ParkingSpotFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ParkingSpotFactory::createMany(10);
    }
}
