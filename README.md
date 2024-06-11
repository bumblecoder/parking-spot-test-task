# parking-spot-test-task

# Parking Management System

Confronted with the ongoing challenge of locating parking spaces within our parking structures in congested
city streets, we embarked on a mission to rethink the parking experience using object-oriented design
principles. Create the necessary APIs to satisfy the requirements below (no frontend work required).
Feel free to use your imagination when making product decisions and determining functionality, while keeping
outcomes simple. We won’t be running the code (or deploying it to prod), so it doesn’t need to be perfect.

## Requirements

- Implement APIs for managing parking spaces
- Use object-oriented design principles
- No frontend work required

## Getting Started

1. run ``sh bootstrap-loc.sh`` file to initiate the project
2. run ``docker compose exec php-fpm composer install`` to install the dependencies
3. run ``docker compose exec php-fpm bin/console doctrine:database:create`` to create the database
4. run ``docker compose exec php-fpm bin/console doctrine:migrations:migrate --no-interaction`` to run the migrations
5. run ``docker compose exec php-fpm bin/console doctrine:fixtures:load -n`` to load fixtures
6. run ``docker compose exec php-fpm bin/phpunit tests --stop-on-failure`` to run the tests (Functional and Unit)
7. run ``cp .env .env.local``
8. replace database url in `.env.local` with the following line: 

``DATABASE_URL="mysqli://app_user:helloworld@mysql:3306/parking_db?serverVersion=16&charset=utf8"``

# API Endpoints

## Get Parking Lot Information

``GET /api/parking-lot``

### Description

Retrieve information about the parking lot, including the total number of spots and available spots.

## Park a Vehicle

``POST /api/parking-spot/{id}/park``

### Description

Park a vehicle in the specified parking spot.

#### Required Parameters

- `id`: The ID of the parking spot.
- `type`: The type of vehicle to be parked. ('car', 'van', 'motorcycle')

## Unpark a Vehicle

### Description

Unpark a vehicle from the specified parking spot.

#### Required Parameters

- `id`: The ID of the parking spot.

## Contributing

We welcome contributions! Read the contributing guidelines for more information.

## License

This project is licensed under the [MIT License](LICENSE).

## Contact

For any questions or feedback, contact us at [email@example.com](mailto:email@example.com).
