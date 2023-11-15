#!/bin/bash

# Docker Compose up with build and detached mode
docker-compose up --build -d

# Execute commands inside the docker container
docker-compose exec sfapp bash -c 'cd sfapp/ && composer install && php bin/console doctrine:migrations:migrate << echo ``'