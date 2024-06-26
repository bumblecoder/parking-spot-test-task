<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\ParkingSpot;
use App\Enum\ParkingSpotType;
use App\Enum\VehicleType;
use App\Exception\ParkingException;
use App\Repository\ParkingSpotRepository;
use App\Service\ParkingValet;
use App\ValueObject\ParkingLotData;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class ParkingSpotController extends AbstractController
{
    public function __construct(
        private readonly ParkingValet $valet,
    ) {}

    #[Route('/api/parking-spot/{id}/park', methods: ['POST'])]
    public function park(
        string $id,
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        $parkingSpot = $em->getRepository(ParkingSpot::class)->find($id);

        if (!$parkingSpot) {
            return $this->json(['message' => 'Parking spot not found.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $vehicleTypeRequested = isset($data['type']) ? (string) $data['type'] : null;
        $vehicleType = $vehicleTypeRequested ? VehicleType::tryFrom(strtolower($vehicleTypeRequested)) : null;

        if (!$vehicleType || !ParkingSpotType::fromVehicleType($vehicleType->value)) {
            return $this->json(['message' => 'Invalid vehicle type.'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->valet->park($vehicleType, $parkingSpot);
        } catch (ParkingException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'Vehicle parked successfully.']);
    }

    #[Route('/api/parking-spot/{id}/unpark', methods: ['POST'])]
    public function unpark(int $id, EntityManagerInterface $em): JsonResponse
    {
        $parkingSpot = $em->getRepository(ParkingSpot::class)->find($id);

        if (!$parkingSpot) {
            return $this->json(['message' => 'Parking spot not found.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->valet->unpark($parkingSpot);
        } catch (ParkingException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'Parking spot successfully released.'], Response::HTTP_OK);
    }

    #[Route('/api/parking-lot', methods: ['GET'])]
    public function getParkingLot(
        ParkingSpotRepository $parkingSpotRepository,
        SerializerInterface $serializer,
        Client $redis,
    ): Response {
        $cachedData = $redis->get('parking_lot_data');

        if ($cachedData) {
            return new Response($cachedData);
        }

        $spots = $parkingSpotRepository->findBy(['isOccupied' => false]);
        $totalSpots = $parkingSpotRepository->count();

        $parkingLotData = new ParkingLotData();
        $parkingLotData
            ->setTotalSpots($totalSpots)
            ->setAvailableSpots($spots);

        $normalizedData = $serializer->normalize($parkingLotData, null, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['occupied', 'vehicle'],
        ]);

        $redis->set(
            'parking_lot_data',
            $serializer->serialize($normalizedData, JsonEncoder::FORMAT),
            'EX',
            3600,
        );

        return $this->json($normalizedData);
    }
}
