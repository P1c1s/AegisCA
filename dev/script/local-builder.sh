#!/bin/bash
set -e

# Silenzia gli errori se il container non esiste o è già fermo
docker stop aegis-ca-local 2>/dev/null || true
docker rm aegis-ca-local 2>/dev/null || true

# Legge la versione dal file VERSION
APP_VERSION=$(< ../VERSION)

# DOCKERFILE="../docker/Dockerfile"
DOCKERFILE="../docker/Dockerfile.dev"


# Ricostruisce l'immagine usando la root come contesto
docker build \
  -f $DOCKERFILE \
  --build-arg APP_VERSION="$APP_VERSION" \
  -t aegis-ca-local \
  --no-cache ..

# Determina il percorso della root del progetto dinamicamente
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

# Avvia il nuovo container per fare i test
docker run -it \
  --name aegis-ca-local \
  --hostname aegis-ca-local \
  -e TZ=Europe/Rome \
  -p 8080:80 \
  -v "$PROJECT_ROOT/web-ui":/var/www/localhost/htdocs/ \
  -v "$PROJECT_ROOT/python":/python \
  aegis-ca-local

  #-v /tmp/aegis-ca-local_data:/data \
