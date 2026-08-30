#!/bin/bash
# Build frontend assets inside a Node 15.8.0 container (matches production Dockerfile).
# Works around node-sass 4.x being incompatible with modern host Node versions,
# and amd64 (Rosetta) emulation on Apple Silicon.
# NOTE: requires ~/.ssh/id_rsa with access to the private GitHub git-dependencies
# (nimahejazi/propertyspot-dashboard, nimahejazi/rk-instant-list).
# If your key is id_ed25519 (or similar), symlink it: ln -s ~/.ssh/id_ed25519 ~/.ssh/id_rsa
set -e
export WORKSPACE=$(pwd)
if [ ! -f ~/.ssh/id_rsa ]; then
  echo "ERROR: ~/.ssh/id_rsa not found (needed for private GitHub git-dependencies)."
  exit 1
fi
SSH_KEY_B64=$(base64 < ~/.ssh/id_rsa)
docker run --rm \
  -e SSH_KEY_B64="$SSH_KEY_B64" \
  -v "$WORKSPACE/laravel":/app \
  -w /app \
  node:15.8.0 bash -ceu '
    mkdir -p ~/.ssh && chmod 0700 ~/.ssh
    echo "$SSH_KEY_B64" | base64 -d > ~/.ssh/id_rsa && chmod 600 ~/.ssh/id_rsa
    ssh-keyscan -t rsa,ecdsa-sha2-nistp256 github.com >> ~/.ssh/known_hosts 2>/dev/null || true
    export GIT_SSH_COMMAND="ssh -o StrictHostKeyChecking=no"
    npm config set spin false
    npm install --no-audit --no-fund
    npm run dev
  '