#!/bin/bash

# Docker Compose up with build and detached mode
docker compose up --build -d

# Execute commands inside the docker container
docker compose exec sfapp bash -c 'cd sfapp/ && php bin/console doctrine:database:drop --force && rm -rf migrations/* && php bin/console doctrine:database:create && php bin/console make:migration && composer install && php bin/console d:m:m --no-interaction && php bin/console d:f:l --no-interaction'

