#!/bin/bash
export WORKSPACE=$(pwd)
export CONTAINER_NAME=propertyspot_laravel
docker-compose up --build -d
./composer install