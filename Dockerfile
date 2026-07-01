FROM theb0ys/apache:latest

# Aggiorna i pacchetti e installa le librerie per OpenSSL e PDO MySQL
# RUN apt-get update && apt-get install -y \
#     libssl-dev \
#     && docker-php-ext-install pdo_mysql \
#     && docker-php-ext-enable pdo_mysql

# Abilita il modulo rewrite di Apache (utile per i reindirizzamenti)
# RUN a2enmod rewrite

# Copia tutti i file del progetto dentro la cartella di Apache nel container
COPY ./web-ui/ /var/www/html/

# Imposta i permessi corretti per l'utente di Apache (www-data)
RUN chown -R www-data:www-data /var/www/html/

COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

# Diciamo a Docker di eseguire lo script ad ogni avvio del container
ENTRYPOINT ["/entrypoint.sh"]