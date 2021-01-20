#!/bin/bash
# The docker inside Jenkins docker is just a link to the docker on the 
# host. the real path of jenkins_home on the host is different from
# the path of jenkins_home on the jenkins container.
# This will result of not linking the laravel folder to the laravel 
# container correctly. The following environment variable will change
# the WORKSPACE variable on the jenkins container
JENKINS_HOME_HOST=/home/ubuntu/jenkins-docker-compose/jenkins_home
# change WORKSPACE variable to jenkins_home on the host
export WORKSPACE=$(echo $WORKSPACE | sed -e "s@$JENKINS_HOME@$JENKINS_HOME_HOST@g")
# We need this to run artisan and composer shell script commands
export CONTAINER_NAME=propertyspot_laravel_test
docker-compose -f docker-compose-test.yml up --build -d
./composer install
./artisan migrate:fresh --seed
./artisan test
if [ $? -ne 0 ]; then
    exit 1
fi
# After the test, remove the containers
docker-compose -f docker-compose-test.yml down