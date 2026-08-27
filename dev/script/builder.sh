#!/bin/bash
set -e

# Chiede conferma all'utente prima di procedere
read -p "Sei sicuro di voler eseguire la build e il push delle immagini Docker AegisCA? (s/N): " CONFIRM
if [[ ! "$CONFIRM" =~ ^[sS]$ ]]; then
  echo "Operazione annullata."
  exit 1
fi

APP_VERSION=$(< ../VERSION)

# Chiede conferma all'utente prima di procedere
read -p "La versione  $APP_VERSION è corretta? (s/N): " CONFIRM
if [[ ! "$CONFIRM" =~ ^[sS]$ ]]; then
  echo "Operazione annullata."
  exit 1
fi

# Inizializza e seleziona il builder multi-architettura se necessario
docker buildx inspect aegis_ca_builder >/dev/null 2>&1 || \
  docker buildx create --name aegis_ca_builder --driver docker-container --use --bootstrap

# Legge la versione dal file VERSION


# Esegue la build indicando il Dockerfile e la radice del progetto come contesto (..)
docker buildx build \
  --platform linux/amd64,linux/arm64 \
  --build-arg APP_VERSION="$APP_VERSION" \
  -f ../docker/Dockerfile \
  -t ghcr.io/p1c1s/aegis-ca:"$APP_VERSION" \
  -t ghcr.io/p1c1s/aegis-ca:v"$APP_VERSION" \
  -t ghcr.io/p1c1s/aegis-ca:latest \
  --push \
  --no-cache ..