# Introduction


Image processing service which can crop and resize images.

# Installation

The project includes a Dockerfile, which is a configuration file used to build a Docker image of the PHP application along with its dependencies. Follow the instructions below:

- docker compose -f docker-compose.yml build
- docker compose -f docker-compose.yml run --rm app composer install --no-interaction --no-progress
- docker compose -f docker-compose.yml up -d

# Usage

GET /test-img.png/resize/900,600/crop/75,300,100,100

# Example

- http://localhost:8080
- http://localhost:8080/test-img.png/resize/900,600/crop/75,300,100,100

# Tests

docker compose -f docker-compose.yml run --rm app vendor/bin/phpunit /app/tests/
