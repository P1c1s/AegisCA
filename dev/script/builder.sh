#!/bin/bash
set -e

# Individua la cartella in cui si trova builder.sh e risale alla radice del progetto
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"

# Chiede conferma all'utente prima di procedere
read -p "Sei sicuro di voler eseguire la build e il push delle immagini Docker AegisCA? (s/N): " CONFIRM
if [[ ! "$CONFIRM" =~ ^[sS]$ ]]; then
  echo "Operazione annullata."
  exit 1
fi

DOCKERFILE="$PROJECT_ROOT/docker/Dockerfile"

# Legge la versione dal file VERSION (verifica se si trova in PROJECT_ROOT o un livello sopra)
if [ -f "$PROJECT_ROOT/VERSION" ]; then
  APP_VERSION=$(< "$PROJECT_ROOT/VERSION")
elif [ -f "$SCRIPT_DIR/../VERSION" ]; then
  APP_VERSION=$(< "$SCRIPT_DIR/../VERSION")
else
  echo "Errore: File VERSION non trovato!"
  exit 1
fi

# Chiede conferma all'utente prima di procedere
read -p "La versione $APP_VERSION è corretta? (s/N): " CONFIRM
if [[ ! "$CONFIRM" =~ ^[sS]$ ]]; then
  echo "Operazione annullata."
  exit 1
fi

# Inizializza e seleziona il builder multi-architettura se necessario
docker buildx inspect aegis_ca_builder >/dev/null 2>&1 || \
  docker buildx create --name aegis_ca_builder --driver docker-container --use --bootstrap

# Esegue la build indicando il Dockerfile e PROJECT_ROOT come contesto
docker buildx build \
  --platform linux/amd64,linux/arm64 \
  --build-arg APP_VERSION="$APP_VERSION" \
  -f "$DOCKERFILE" \
  -t ghcr.io/p1c1s/aegis-ca:"$APP_VERSION" \
  -t ghcr.io/p1c1s/aegis-ca:v"$APP_VERSION" \
  -t ghcr.io/p1c1s/aegis-ca:latest \
  --push \
  --no-cache "$PROJECT_ROOT"