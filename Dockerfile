FROM alpine:3.19

# Metadati
LABEL org.opencontainers.image.title="Aegis CA"
LABEL org.opencontainers.image.description="Aegis CA is a lightweight, secure, and completely self-contained web application for creating, signing, and managing a Local Certificate Authority (CA) and its corresponding self-signed SSL/TLS certificates."
LABEL org.opencontainers.image.source="https://github.com/P1c1s/AegisCA"
LABEL org.opencontainers.image.licenses="Apache-2.0"
LABEL org.opencontainers.image.authors="p1c1s"

# 1. Aggiorna i pacchetti e installa le dipendenze
RUN apk update && apk add --no-cache \
    apache2 \
    php83 \
    php83-apache2 \
    php83-mysqli \
    php83-json \
    php83-pdo \
    php83-pdo_mysql \
    php83-session \
    php83-openssl \
    curl \
    mariadb \
    mariadb-client \
    mariadb-dev \
    build-base \
    python3 \
    py3-pip \
    python3-dev \
    libffi-dev \
    bash \
    tzdata

# 2. Installa librerie Python
RUN pip3 install --break-system-packages tabulate mysql-connector-python bcrypt

# 3. Configura MariaDB tramite file dedicato (pulisce conf di default)
RUN mkdir -p /data /run/mysqld && \
    chown -R mysql:mysql /data /run/mysqld && \
    rm -rf /etc/my.cnf.d/*
COPY ./mariadb.cnf /etc/my.cnf.d/mariadb.cnf

# 4. Configura Apache tramite file dedicato (pulisce conf di default)
RUN rm -rf /etc/apache2/conf.d/* && mkdir -p /run/apache2
COPY ./httpd.conf /etc/apache2/httpd.conf

# 5. Copia la Web UI
RUN rm -f /var/www/localhost/htdocs/index.html
COPY ./web-ui/ /var/www/localhost/htdocs/

# 6. Copia lo script aegis-ca.py dentro /bin
COPY ./aegis-ca.py /bin/aegis-ca.py
RUN chmod +x /bin/aegis-ca.py && \
    ln -s /bin/aegis-ca.py /usr/local/bin/aegis-ca

# 7. Permessi e directory di lavoro
RUN chown -R apache:apache /var/www/localhost/htdocs/
WORKDIR /

# 8. Entrypoint
COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# 9. Healthcheck
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
  CMD curl -fL -s -o /dev/null http://localhost:80/ || exit 1

EXPOSE 80
VOLUME ["/data"]

ENTRYPOINT ["/entrypoint.sh"]