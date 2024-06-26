<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\ParkingSpot;
use App\Entity\Vehicle;
use App\Enum\VehicleType;
use App\Exception\ParkingException;
use App\Specification\ParkingSpecificationInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ParkingValet
{
    public function __construct(
        private EntityManagerInterface $em,
        private iterable|ParkingSpecificationInterface $specifications,
    ) {
        foreach ($specifications as $specification) {
            if (!$specification instanceof ParkingSpecificationInterface) {
                throw new \InvalidArgumentException('All specifications must implement ParkingSpecificationInterface.');
            }
        }
    }

    /**
     * @throws ParkingException
     */
    public function park(VehicleType $vehicleType, ParkingSpot $parkingSpot): void
    {
        foreach ($this->specifications as $specification) {
            if (!$specification->isSatisfiedBy($vehicleType, $parkingSpot)) {
                throw new ParkingException($specification->getMessage());
            }
        }

        $vehicle = new Vehicle();
        $vehicle->setType($vehicleType);
        $parkingSpot
            ->setIsOccupied(true)
            ->setVehicle($vehicle)
        ;

        $this->em->persist($vehicle);
        $this->em->flush();
    }

    /**
     * @throws ParkingException
     */
    public function unpark(ParkingSpot $parkingSpot): void
    {
        if (false === $parkingSpot->isOccupied()) {
            throw new ParkingException('Parking spot is free.');
        }

        $parkingSpot->setVehicle(null);
        $parkingSpot->setIsOccupied(false);

        $this->em->flush();
    }
}
