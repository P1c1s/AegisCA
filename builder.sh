#!/bin/bash

# docker buildx create --name mio_builder --driver docker-container --use
# docker buildx inspect --bootstrap
# echo "INCOLLA_QUI_IL_TUO_TOKEN_GITHUB" | docker login ghcr.io -u IL_TUO_UTENTE_GITHUB --password-stdin
# docker buildx build --platform linux/amd64,linux/arm64 -t ghcr.io/p1c1s/aegis-ca:latest --push .

# docker stop aegis-ca && docker rm aegis-ca && docker build -t aegis-ca-local . && docker run -dit --name aegis-ca aegis-ca-local
docker stop aegis-ca && docker rm aegis-ca && docker build -t aegis-ca-local . && docker run -dit --name aegis-ca -v /home/lorenzo/Documenti/GitHubProjects/AegisCA/web-ui:/var/www/html aegis-ca-local