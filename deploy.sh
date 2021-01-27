#!/bin/bash
# The docker inside Jenkins docker is just a link to the docker on the 
# host. the real path of jenkins_home on the host is different from
# the path of jenkins_home on the jenkins container.
# This will result of not linking the laravel folder to the laravel 
# container correctly. The following environment variable will change
# the WORKSPACE variable on the jenkins container

# $JENKINS_HOME_HOST is the full path of jenkins_home folder on the
# host. We need it because jenkins runs docker on host not inside its
# container, so docker won't have access to the jekins_home there.
if [ -z "$JENKINS_HOME_HOST" ]; then
    echo 'JENKINS_HOME_HOST is not set. If not running through jenkins, set it to `$(pwd)`.'
    exit 1
fi
# If $JENKINS_HOME is not set, it means this shell script is not running
# by Jenkins, so we set WORKSPACE manually
if [ -z "$JENKINS_HOME" ]; then
    export WORKSPACE=$(pwd)
else 
    # $WORKSPACE AND $JENKINS_HOME are set by Jenkins, since docker 
    # inside jenkins contianer is just a link to the host docker
    # we cannot access WORKSPACE set by the jenkins in the container,
    # we need to use host jenkins_home
    # $WORKSPACE is $JENKINS_HOME + the current project folder;
    # we need JENKINS_HOME_HOST + current project folder, since there is
    # no variable for that, we need to make it
    # changes WORKSPACE variable to jenkins_home on the host
    # We need this to run artisan and composer shell script commands
    export WORKSPACE=$(echo $WORKSPACE | sed -e "s@$JENKINS_HOME@$JENKINS_HOME_HOST@g")
fi
# We need CONTAINER_NAME to run artisan and composer shell script commands
# since container name on test and deploy are different to prevent 
# disruption of the production container while we test the release
export CONTAINER_NAME=propertyspot_laravel
cp ./laravel/.env ./laravel/.env.bak
cp ./laravel/.env.prod ./laravel/.env
./composer install
docker-compose up --build -d
./artisan migrate --force