#!/bin/bash
export WORKSPACE=$(pwd)
export CONTAINER_NAME=propertyspot_laravel
docker-compose -f docker-compose-dev.yml up --build -d
./composer install
./artisan migrate:fresh --seed