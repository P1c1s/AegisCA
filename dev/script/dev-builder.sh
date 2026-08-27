#!/bin/bash
set -e

# Determina il percorso della root del progetto dinamicamente
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

# Legge la versione dal file VERSION
APP_VERSION=$(< ../VERSION)

# Silenzia gli errori se il container non esiste o è già fermo
docker stop aegis-ca-dev 2>/dev/null || true
docker rm aegis-ca-dev 2>/dev/null || true

# DOCKERFILE="../docker/Dockerfile"
DOCKERFILE="../docker/Dockerfile.dev"


# Ricostruisce l'immagine usando la root come contesto
docker build \
  -f $DOCKERFILE \
  --build-arg APP_VERSION="$APP_VERSION" \
  -t aegis-ca-dev \
  --no-cache \
  "$PROJECT_ROOT"


# Avvia il nuovo container per fare i test
docker run -it \
  --name aegis-ca-dev \
  --hostname aegis-ca-dev \
  -e TZ=Europe/Rome \
  -p 8080:80 \
  -v /tmp/aegis-ca-dev_data:/data \
  -v "$PROJECT_ROOT/web-ui":/var/www/localhost/htdocs/ \
  -v "$PROJECT_ROOT/dev/php":/php \
  -v "$PROJECT_ROOT/python":/python \
  aegis-ca-dev
