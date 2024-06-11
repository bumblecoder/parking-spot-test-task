<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Enum\ParkingSpotType;
use App\Enum\VehicleType;
use App\Factory\ParkingSpotFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ParkingControllerTest extends WebTestCase
{
    use ResetDatabase;
    use Factories;

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        static::ensureKernelShutdown();
        $this->client = static::createClient();
    }

    public function testParkGetParkingLots(): void
    {
        ParkingSpotFactory::createOne(['type' => ParkingSpotType::fromVehicleType(VehicleType::CAR->value)]);
        $this->client->request(Request::METHOD_GET, '/api/parking-lot');

        $response = $this->client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJson($response);
        $this->assertJsonStringEqualsJsonString('{"totalSpots":1,"availableSpots":[{"id":1,"type":"regular"}]}', $response);
    }

    /**
     * @dataProvider validVanDataProvider
     */
    public function testParkWithValidData(string $type): void
    {
        ParkingSpotFactory::createOne(['type' => ParkingSpotType::VAN]);

        $this->client->request(Request::METHOD_POST, '/api/parking-spot/1/park', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['type' => $type])
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString(
            '{"message":"Vehicle parked successfully."}',
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * @dataProvider invalidVanDataProvider
     */
    public function testParkWithInvalidData(string|int $type): void
    {
        ParkingSpotFactory::createOne(['type' => ParkingSpotType::VAN]);

        $this->client->request(Request::METHOD_POST, '/api/parking-spot/1/park', [], [], ['CONTENT_TYPE' => 'application/json'],
            json_encode(['type' => $type])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonStringEqualsJsonString(
            '{"message":"Invalid vehicle type."}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testParkWithParkingSpotNotFound(): void
    {
        ParkingSpotFactory::createOne(['type' => ParkingSpotType::VAN]);

        $this->client->request(Request::METHOD_POST, '/api/parking-spot/999/park', [], [], [], json_encode(['type' => 'car']));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertJsonStringEqualsJsonString(
            '{"message":"Parking spot not found."}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testParkWithNoVehicleType(): void
    {
        ParkingSpotFactory::createOne(['type' => ParkingSpotType::VAN]);

        $this->client->request(Request::METHOD_POST, '/api/parking-spot/1/park');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonStringEqualsJsonString(
            '{"message":"Invalid vehicle type."}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testUnparkWithOccupiedParkingSpot(): void
    {
        ParkingSpotFactory::createOne(['type' => ParkingSpotType::REGULAR]);

        $this->client->request(Request::METHOD_POST, '/api/parking-spot/1/park', [], [], [], json_encode(['type' => 'car']));

        $this->assertResponseIsSuccessful();

        $this->client->request(Request::METHOD_POST, '/api/parking-spot/1/unpark');

        $this->assertResponseIsSuccessful();
        $this->assertSame('Parking spot successfully released.', json_decode($this->client->getResponse()->getContent(), true)['message']);
    }

    public function testUnparkWithFreeParkingSpot(): void
    {
        ParkingSpotFactory::createOne();

        $this->client->request(Request::METHOD_POST, '/api/parking-spot/1/unpark');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertSame('Parking spot is free.', json_decode($this->client->getResponse()->getContent(), true)['error']);
    }

    public function testUnparkWithNonExistentParkingSpot(): void
    {
        $this->client->request(Request::METHOD_POST, '/api/parking-spot/999/unpark');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertJsonStringEqualsJsonString(
            '{"message":"Parking spot not found."}',
            $this->client->getResponse()->getContent()
        );
    }

    public function validVanDataProvider(): \Generator
    {
        yield ['VAN'];
        yield ['van'];
        yield ['Van'];
    }

    public function invalidVanDataProvider(): \Generator
    {
        yield ['123'];
        yield ['QWE'];
        yield ['Test'];
        yield [123];
    }
}
