<?php

declare(strict_types=1);

namespace App\Tests\Unit\Specification;

use App\Entity\ParkingSpot;
use App\Enum\ParkingSpotType;
use App\Enum\VehicleType;
use App\Specification\VehicleTypeMatchingSpecification;
use PHPUnit\Framework\TestCase;

class VehicleTypeMatchingSpecificationTest extends TestCase
{
    public function testIsSatisfiedByWithMatchingTypes(): void
    {
        $parkingSpot = $this->createMock(ParkingSpot::class);
        $parkingSpot->method('getType')->willReturn(ParkingSpotType::REGULAR);

        $specification = new VehicleTypeMatchingSpecification();
        $result = $specification->isSatisfiedBy(VehicleType::CAR, $parkingSpot);

        $this->assertTrue($result);
    }

    public function testIsSatisfiedByWithNonMatchingTypes(): void
    {
        $parkingSpot = $this->createMock(ParkingSpot::class);
        $parkingSpot->method('getType')->willReturn(ParkingSpotType::MOTORCYCLE);

        $specification = new VehicleTypeMatchingSpecification();
        $result = $specification->isSatisfiedBy(VehicleType::CAR, $parkingSpot);

        $this->assertFalse($result);
    }

    public function testIsSatisfiedByWithInvalidVehicleType(): void
    {
        $parkingSpot = $this->createMock(ParkingSpot::class);
        $parkingSpot->method('getType')->willReturn(ParkingSpotType::VAN);

        $specification = new VehicleTypeMatchingSpecification();
        $result = $specification->isSatisfiedBy(VehicleType::CAR, $parkingSpot);

        $this->assertFalse($result);
    }

    public function testGetMessage(): void
    {
        $specification = new VehicleTypeMatchingSpecification();
        $message = $specification->getMessage();

        $this->assertEquals('This vehicle type does not match parking spot.', $message);
    }
}
