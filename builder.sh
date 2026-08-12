#!/bin/bash
set -e

# Inizializza e seleziona il builder multi-architettura se necessario
docker buildx inspect aegis_ca_builder >/dev/null 2>&1 || \
  docker buildx create --name aegis_ca_builder --driver docker-container --use --bootstrap

# Legge la versione dal file VERSION
APP_VERSION=$(< web-ui/config/VERSION)

# Esegue la build multi-architettura e il push
docker buildx build \
  --platform linux/amd64,linux/arm64 \
  -t ghcr.io/p1c1s/aegis-ca:"$APP_VERSION" \
  -t ghcr.io/p1c1s/aegis-ca:v"$APP_VERSION" \
  -t ghcr.io/p1c1s/aegis-ca:latest \
  --push \
  --no-cache .
