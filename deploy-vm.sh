#!/bin/bash
# VM-side deploy: pull the prebuilt image, recreate, migrate.
# Runs ON the GCP VM (~/opt/propertyspot/app). Never build here — 1 GB OOMs.
# First deploy only: afterwards run `./artisan db:seed` and change the seeded
# admin password (see GCP_DEPLOY.md §6).
set -euo pipefail

# $JENKINS_HOME_HOST quirk inherited from deploy.sh/test.sh (artisan wrapper
# resolves container paths against WORKSPACE); required even outside Jenkins.
if [ -z "$JENKINS_HOME_HOST" ]; then
    echo 'JENKINS_HOME_HOST is not set. Run as: JENKINS_HOME_HOST=$(pwd) ./deploy-vm.sh'
    exit 1
fi
export CONTAINER_NAME=propertyspot_laravel

docker compose -f docker-compose.yml -f docker-compose.prod.yml pull
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d
sleep 20  # give mysql time to accept connections on cold start
./artisan migrate --force