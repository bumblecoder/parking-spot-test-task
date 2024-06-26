<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\ParkingSpot;
use App\Entity\Vehicle;
use App\Enum\VehicleType;
use App\Exception\ParkingException;
use App\Service\ParkingValet;
use App\Specification\ParkingSpecificationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class ParkingValetTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private ParkingSpecificationInterface $specification1;
    private ParkingSpecificationInterface $specification2;
    private ParkingValet $parkingValet;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->specification1 = $this->createMock(ParkingSpecificationInterface::class);
        $this->specification2 = $this->createMock(ParkingSpecificationInterface::class);

        $this->parkingValet = new ParkingValet(
            $this->entityManager,
            [$this->specification1, $this->specification2]
        );
    }

    /**
     * @throws ParkingException
     */
    public function testParkWithValidSpecifications(): void
    {
        $parkingSpot = $this->createMock(ParkingSpot::class);
        $parkingSpot->method('isOccupied')->willReturn(false);

        $this->specification1->method('isSatisfiedBy')->willReturn(true);
        $this->specification2->method('isSatisfiedBy')->willReturn(true);

        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        $this->parkingValet->park(VehicleType::CAR, $parkingSpot);
    }

    public function testParkWithInvalidSpecifications(): void
    {
        $this->expectException(ParkingException::class);
        $this->expectExceptionMessage('Some error message');

        $parkingSpot = $this->createMock(ParkingSpot::class);
        $parkingSpot->method('isOccupied')->willReturn(false);

        $this->specification1->method('isSatisfiedBy')->willReturn(false);
        $this->specification1->method('getMessage')->willReturn('Some error message');

        $this->parkingValet->park(VehicleType::CAR, $parkingSpot);
    }

    /**
     * @throws ParkingException
     */
    public function testUnparkWithOccupiedSpot(): void
    {
        $parkingSpot = $this->createMock(ParkingSpot::class);
        $parkingSpot->method('isOccupied')->willReturn(true);

        $vehicle = $this->createMock(Vehicle::class);

        $vehicleRepository = $this->createMock(EntityRepository::class);
        $vehicleRepository->method('findOneBy')->willReturn($vehicle);

        $this->entityManager->method('getRepository')->willReturn($vehicleRepository);

        $this->entityManager->expects($this->once())->method('flush');

        $this->parkingValet->unpark($parkingSpot);
    }

    public function testUnparkWithFreeSpot(): void
    {
        $this->expectException(ParkingException::class);
        $this->expectExceptionMessage('Parking spot is free.');

        $parkingSpot = $this->createMock(ParkingSpot::class);
        $parkingSpot->method('isOccupied')->willReturn(false);

        $this->parkingValet->unpark($parkingSpot);
    }
}
