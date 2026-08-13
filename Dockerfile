FROM alpine:3.19

# Metadati
LABEL org.opencontainers.image.title="Aegis CA"
LABEL org.opencontainers.image.description="Aegis CA is a lightweight, secure, and completely self-contained web application for creating, signing, and managing a Local Certificate Authority (CA) and its corresponding self-signed SSL/TLS certificates."
LABEL org.opencontainers.image.source="https://github.com/p1c1s/aegis-ca"
LABEL org.opencontainers.image.licenses="Apache-2.0"
LABEL org.opencontainers.image.authors="p1c1s"

# 1. Aggiorna i pacchetti e installa Apache, PHP, MariaDB, Python e i compilatori necessari
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

# 2. Installa le librerie Python specifiche per MariaDB e sicurezza
RUN pip3 install --break-system-packages tabulate mysql-connector-python bcrypt

# 3. Configura MariaDB per usare /data/ come directory dei dati
RUN mkdir -p /data /run/mysqld && \
    chown -R mysql:mysql /data /run/mysqld && \
    sed -i 's|^datadir.*=.*|datadir = /data|' /etc/my.cnf.d/mariadb-server.cnf

# 4. Abilita il modulo rewrite, imposta ServerName e sposta la DocumentRoot su htdocs/public
RUN sed -i 's/#LoadModule rewrite_module/LoadModule rewrite_module/' /etc/apache2/httpd.conf && \
    sed -i 's/#ServerName www.example.com:80/ServerName localhost:80/' /etc/apache2/httpd.conf && \
    sed -i 's|DocumentRoot "/var/www/localhost/htdocs"|DocumentRoot "/var/www/localhost/htdocs/public"|' /etc/apache2/httpd.conf && \
    sed -i 's|<Directory "/var/www/localhost/htdocs">|<Directory "/var/www/localhost/htdocs/public">|' /etc/apache2/httpd.conf && \
    sed -i '/<Directory "\/var\/www\/localhost\/htdocs\/public">/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/httpd.conf && \
    mkdir -p /run/apache2

# 5. Svuota la cartella di default e copia i file della tua Web UI (Nota: su Alpine il path è htdocs)
RUN rm -f /var/www/localhost/htdocs/index.html
COPY ./web-ui/ /var/www/localhost/htdocs/

# 6. Copia il file db.sql dentro /data (il database lo leggerà da lì durante l'entrypoint)
COPY db.sql /data/db.sql

# 7. Copia lo script aegis-ca.py dentro /bin
COPY ./aegis-ca.py /bin/aegis-ca.py
RUN chmod +x /bin/aegis-ca.py && \
    ln -s /bin/aegis-ca.py /usr/local/bin/aegis-ca

# 8. Imposta i permessi corretti per Apache (su Alpine l'utente di Apache si chiama apache)
RUN chown -R apache:apache /var/www/localhost/htdocs/
WORKDIR /


# 9. Configura l'entrypoint personalizzato
COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# 10. Healthcheck per monitorare la Web UI
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
  CMD curl -f -s http://localhost:80/ || exit 1

# 11. Espone la porta 80 (e la 3306 opzionale se vuoi accedere al DB da fuori)
EXPOSE 80

VOLUME ["/data"]

ENTRYPOINT ["/entrypoint.sh"]