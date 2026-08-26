FROM alpine:3.19

LABEL org.opencontainers.image.title="Aegis CA" \
      org.opencontainers.image.description="Aegis CA is a lightweight, secure, and completely self-contained web application for creating, signing, and managing a Local Certificate Authority (CA) and its corresponding self-signed SSL/TLS certificates." \
      org.opencontainers.image.source="https://github.com/P1c1s/AegisCA" \
      org.opencontainers.image.licenses="Apache-2.0" \
      org.opencontainers.image.authors="p1c1s"

ARG APP_VERSION=0.0.0
ENV AEGIS_VERSION=$APP_VERSION

# 1. Dipendenze di Runtime + Pip + Pulizia in un solo LAYER (Senza mariadb-client)
RUN apk add --no-cache \
    apache2 \
    bash \
    curl \
    mariadb \
    php83 \
    php83-apache2 \
    php83-json \
    php83-mysqli \
    php83-openssl \
    php83-pdo \
    php83-pdo_mysql \
    php83-session \
    python3 \
    py3-pip \
    py3-cryptography \
    tzdata && \
    # Installazione pacchetti Python leggeri
    pip install --no-cache-dir --break-system-packages pymysql tabulate bcrypt && \
    # Pulizia cache e spazzatura
    find /usr/lib/python* -type f -name '*.pyc' -delete && \
    find /usr/lib/python* -type d -name '__pycache__' -delete && \
    rm -rf /usr/share/man/* \
           /usr/share/doc/* \
           /usr/share/info/* \
           /var/cache/apk/* \
           /root/.cache \
           /var/www/localhost/htdocs/* \
           /usr/share/mariadb/charsets \
           /usr/share/mariadb/english \
           /usr/bin/mysqlslap \
           /usr/bin/mysqlbinlog \
           /usr/bin/mysql_embedded \
           /usr/bin/mysql_ldb \
           /usr/bin/mysql_plugin \
           /usr/bin/mysql_tzinfo_to_sql 

# 2. Configura MariaDB
RUN mkdir -p /data /run/mysqld && \
    chown -R mysql:mysql /data /run/mysqld && \
    rm -rf /etc/my.cnf.d/*
COPY docker/config/mariadb.cnf /etc/my.cnf.d/mariadb.cnf

# 3. Configura Apache
RUN rm -rf /etc/apache2/conf.d/* && mkdir -p /run/apache2
COPY docker/config/httpd.conf /etc/apache2/httpd.conf

# 4. Copia la Web UI
RUN rm -f /var/www/localhost/htdocs/index.html
COPY ./web-ui/ /var/www/localhost/htdocs/

# 5. Copia lo script aegis-ca.py dentro /bin
COPY ./aegis-ca.py /bin/aegis-ca.py
RUN chmod +x /bin/aegis-ca.py && \
    ln -s /bin/aegis-ca.py /usr/local/bin/aegis-ca

# 6. Permessi e directory di lavoro
RUN chown -R apache:apache /var/www/localhost/htdocs/
WORKDIR /

# 7. Entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# 8. Healthcheck
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
  CMD curl -fL -s -o /dev/null http://localhost:80/ || exit 1

  
EXPOSE 80
VOLUME ["/data"]

ENTRYPOINT ["/entrypoint.sh"]