#!/bin/bash
export WORKSPACE=$(pwd)
export CONTAINER_NAME=propertyspot_laravel
docker-compose -f docker-compose-dev.yml up --build -d
./composer install
sleep 20
./artisan migrate:fresh --seed