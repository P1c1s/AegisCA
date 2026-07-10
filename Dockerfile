FROM alpine:3.19

# 1. Aggiorna i pacchetti e installa Apache, PHP, estensioni e MariaDB
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
    bash \
    tzdata

# 2. Configura MariaDB per usare /data/ come directory dei dati
RUN mkdir -p /data /run/mysqld && \
    chown -R mysql:mysql /data /run/mysqld && \
    sed -i 's|^datadir.*=.*|datadir = /data|' /etc/my.cnf.d/mariadb-server.cnf

# 3. Abilita il modulo rewrite, imposta ServerName e sposta la DocumentRoot su htdocs/public
RUN sed -i 's/#LoadModule rewrite_module/LoadModule rewrite_module/' /etc/apache2/httpd.conf && \
    sed -i 's/#ServerName www.example.com:80/ServerName localhost:80/' /etc/apache2/httpd.conf && \
    sed -i 's|DocumentRoot "/var/www/localhost/htdocs"|DocumentRoot "/var/www/localhost/htdocs/public"|' /etc/apache2/httpd.conf && \
    sed -i 's|<Directory "/var/www/localhost/htdocs">|<Directory "/var/www/localhost/htdocs/public">|' /etc/apache2/httpd.conf && \
    sed -i '/<Directory "\/var\/www\/localhost\/htdocs\/public">/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/httpd.conf && \
    mkdir -p /run/apache2

# 4. Svuota la cartella di default e copia i file della tua Web UI (Nota: su Alpine il path è htdocs)
RUN rm -f /var/www/localhost/htdocs/index.html
COPY ./web-ui/ /var/www/localhost/htdocs/

# 5. Copia il file db.sql dentro /data (il database lo leggerà da lì durante l'entrypoint)
COPY db.sql /data/db.sql

# 6. Imposta i permessi corretti per Apache (su Alpine l'utente di Apache si chiama apache)
RUN chown -R apache:apache /var/www/localhost/htdocs/
WORKDIR /

# 7. Configura l'entrypoint personalizzato
COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Esponi la porta 80 (e la 3306 opzionale se vuoi accedere al DB da fuori)
EXPOSE 80

VOLUME ["/data"]

ENTRYPOINT ["/entrypoint.sh"]