#!/bin/bash
export WORKSPACE=$(pwd)
export CONTAINER_NAME=propertyspot_laravel
docker-compose -f docker-compose-dev.yml up -d --build 
./composer install
./artisan migrate:fresh --seed