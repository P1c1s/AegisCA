#!/bin/bash

# 1. Avvia i servizi in background
service mariadb start
service apache2 start

# 2. Importa il database (se il file esiste)
if [ -f "/var/www/html/db.sql" ]; then
    mysql < /var/www/html/db.sql
fi

# 3. TRUCCO: Tiene il container attivo monitorando i log di Apache
# Questo comando non finisce mai, costringendo Docker a lasciare il container acceso.
tail -f /var/log/apache2/access.log /var/log/apache2/error.log