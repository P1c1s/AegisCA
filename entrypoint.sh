#!/bin/bash
set -e

# ==========================================
# Configurazione del Timezone (Fuso Orario)
# ==========================================
if [ ! -z "$TZ" ]; then
    echo "[INFO] Configurazione del fuso orario su: $TZ"
    
    # Configura il fuso orario a livello di sistema operativo (Alpine)
    cp /usr/share/zoneinfo/$TZ /etc/localtime
    echo "$TZ" > /etc/timezone
    
    # Forza il fuso orario in PHP creando un file di configurazione prioritario
    mkdir -p /etc/php83/conf.d
    echo "date.timezone = \"$TZ\"" > /etc/php83/conf.d/99_timezone.ini
fi

# ==========================================
# 1. Inizializza MariaDB se la cartella è vuota
# ==========================================
if [ ! -d "/data/mysql" ]; then
    echo "[INFO] Prima esecuzione: Inizializzazione di MariaDB in /data..."
    mysql_install_db --user=mysql --datadir=/data > /dev/null

    # Avvia temporaneamente MariaDB in background per la configurazione iniziale
    mysqld_safe --user=mysql --datadir=/data &
    pid="$!"
    
    # Attendi che il database sia effettivamente pronto
    until mysqladmin ping &>/dev/null; do
        sleep 1
    done

    # ==========================================
    # 2. Importa il database
    # ==========================================
    # Controlla prima in /tmp (Best Practice con i Volumi Docker)
    if [ -f "/tmp/db.sql" ]; then
        echo "[INFO] Importazione del file db.sql da /tmp..."
        mysql < /tmp/db.sql
        echo "[INFO] Importazione completata."
    elif [ -f "/data/db.sql" ]; then
        echo "[INFO] Importazione del file db.sql da /data..."
        mysql < /data/db.sql
        echo "[INFO] Importazione completata."
    else
        echo "[WARN] Nessun file db.sql trovato, salto l'importazione."
    fi

    # Spegne il MariaDB temporaneo per poterlo riavviare pulito
    mysqladmin -u root shutdown
    wait "$pid"
fi

# ==========================================
# 3. Avvia MariaDB definitivo in background
# ==========================================
echo "[INFO] Avvio di MariaDB..."
mysqld_safe --user=mysql --datadir=/data &

# ==========================================
# 4. Avvia Apache in primo piano (Foreground)
# ==========================================
echo "[INFO] Avvio di Apache..."
exec httpd -D FOREGROUND