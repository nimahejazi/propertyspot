#!/bin/bash
# For comments, refer to test.sh
JENKINS_HOME_HOST=/home/ubuntu/jenkins-docker-compose/jenkins_home
export CONTAINER_NAME=propertyspot_laravel
export WORKSPACE=$(echo $WORKSPACE | sed -e "s@$JENKINS_HOME@$JENKINS_HOME_HOST@g")
export CONTAINER_NAME=propertyspot_laravel
docker-compose up --build -d
./composer install
./artisan migrate
chgrp -R www-data laravel/storage laravel/bootstrap/cache
chmod -R ug+rwx laravel/storage laravel/bootstrap/cache