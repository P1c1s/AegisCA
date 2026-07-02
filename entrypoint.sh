#!/bin/bash

# 1. Inizializza MariaDB se la cartella dei dati è vuota
if [ ! -d "/data/mysql" ]; then
    echo "[INFO] Prima esecuzione: Inizializzazione di MariaDB in /data..."
    mysql_install_db --user=mysql --datadir=/data > /dev/null

    # Avvia temporaneamente MariaDB per creare i permessi e importare il SQL
    mysqld_safe --user=mysql --datadir=/data &
    pid="$!"
    
    # Attendi che il database sia effettivamente pronto a ricevere comandi
    until mysqladmin ping &>/dev/null; do
        sleep 1
    done

    # 2. Importa il database (usando il path corretto in cui hai copiato il file)
    if [ -f "/data/db.sql" ]; then
        echo "[INFO] Importazione del file db.sql..."
        # Nota: Se nel tuo db.sql NON c'è "CREATE DATABASE", scommenta la riga sotto:
        # mysql -e "CREATE DATABASE IF NOT EXISTS mia_app;"
        mysql < /data/db.sql
        echo "[INFO] Importazione completata."
    elif [ -f "/tmp/db.sql" ]; then
        echo "[INFO] Importazione del file db.sql da /tmp..."
        mysql < /tmp/db.sql
        echo "[INFO] Importazione completata."
    else
        echo "[WARN] db.sql non trovato, salto l'importazione."
    fi

    # Spegne il MariaDB temporaneo per riavviarlo pulito dopo
    mysqladmin -u root shutdown
    wait "$pid"
fi

# 3. Avvia MariaDB definitivo in background
echo "[INFO] Avvio di MariaDB..."
mysqld_safe --user=mysql --datadir=/data &

# 4. TRUCCO AGGIORNATO PER ALPINE: Avvia Apache in primo piano
# Invece di usare "service" e poi il "tail -f", lanciamo Apache direttamente 
# in modalità "FOREGROUND". Questo terrà il container attivo e manderà i log 
# di errore direttamente sulla tua console di Docker.
echo "[INFO] Avvio di Apache..."
exec httpd -D FOREGROUND