#!/bin/bash
export WORKSPACE=$(pwd)
export CONTAINER_NAME=propertyspot_laravel
docker-compose -f docker-compose-dev.yml up -d --build 
./composer install
# Wait for MySQL to accept connections before migrating (avoids "Connection refused")
until docker exec propertyspot_db mysqladmin ping -hlocalhost --silent; do sleep 2; done
./artisan migrate:fresh --seed