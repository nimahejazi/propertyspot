#!/bin/bash
# Build the prod image on this Mac (amd64) and push to Artifact Registry.
# The VM never builds — it only pulls this image (see deploy-vm.sh).
# Run from the repo root. Requires: docker buildx, gcloud authed, ~/.ssh/id_rsa
# with access to the private nimahejazi/* npm/composer packages (SSH_KEY arg).
set -euo pipefail

PROJECT_ID="${GCP_PROJECT_ID:?Set GCP_PROJECT_ID (or edit PROJECT_ID in docker-compose.prod.yml)}"
REGION="${GCP_REGION:-us-west1}"
IMAGE="$REGION-docker.pkg.dev/$PROJECT_ID/propertyspot/app:latest"

gcloud auth configure-docker "$REGION-docker.pkg.dev" >/dev/null

docker buildx build \
  --platform linux/amd64 \
  -f Dockerfile \
  --build-arg SSH_KEY="$(cat ~/.ssh/id_rsa)" \
  -t "$IMAGE" \
  --push \
  .

echo "pushed $IMAGE"