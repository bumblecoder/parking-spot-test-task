<?php

declare(strict_types=1);

namespace App\Tests\Unit\Specification;

use App\Entity\ParkingSpot;
use App\Enum\VehicleType;
use App\Specification\ParkingSpotOccupiedSpecification;
use PHPUnit\Framework\TestCase;

class ParkingSpotOccupiedSpecificationTest extends TestCase
{
    public function testIsSatisfiedByWhenSpotIsNotOccupied(): void
    {
        $parkingSpot = $this->createMock(ParkingSpot::class);
        $parkingSpot->method('isOccupied')->willReturn(false);

        $specification = new ParkingSpotOccupiedSpecification();
        $result = $specification->isSatisfiedBy(VehicleType::CAR, $parkingSpot);

        $this->assertTrue($result);
    }

    public function testIsSatisfiedByWhenSpotIsOccupied(): void
    {
        $parkingSpot = $this->createMock(ParkingSpot::class);
        $parkingSpot->method('isOccupied')->willReturn(true);

        $specification = new ParkingSpotOccupiedSpecification();
        $result = $specification->isSatisfiedBy(VehicleType::CAR, $parkingSpot);

        $this->assertFalse($result);
    }

    public function testGetMessage(): void
    {
        $specification = new ParkingSpotOccupiedSpecification();
        $message = $specification->getMessage();

        $this->assertEquals('Parking spot is already occupied.', $message);
    }
}
