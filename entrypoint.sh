#!/bin/sh
set -e

# ==========================================
# Configurazione Valori Default DB
# ==========================================
DB_NAME="${DB_NAME:-aegis_ca}"
DB_USER="${DB_USER:-athena}"
DB_PASS="${DB_PASS:-goat-snake-gorgon}"

# ==========================================
# Configurazione del Timezone
# ==========================================
if [ -n "$TZ" ]; then
    echo "[INFO] Configurazione del fuso orario su: $TZ"
    if [ -f "/usr/share/zoneinfo/$TZ" ]; then
        cp "/usr/share/zoneinfo/$TZ" /etc/localtime
        echo "$TZ" > /etc/timezone

        PHP_INI_DIR="/etc/php83/conf.d"
        [ ! -d "$PHP_INI_DIR" ] && PHP_INI_DIR="/etc/php8/conf.d"

        mkdir -p "$PHP_INI_DIR"
        echo "date.timezone = \"$TZ\"" > "$PHP_INI_DIR/99_timezone.ini"
    fi
fi

# Assicura le directory necessarie e i permessi corretti per MariaDB
mkdir -p /run/mysqld /data
chown -R mysql:mysql /run/mysqld /data

# ==========================================
# 1. Inizializza MariaDB se la cartella è vuota
# ==========================================
if [ ! -d "/data/mysql" ]; then
    echo "[INFO] Prima esecuzione: Inizializzazione di MariaDB in /data..."
    mysql_install_db --user=mysql --datadir=/data > /dev/null

    # Avvia temporaneamente MariaDB in background per la configurazione iniziale
    mysqld_safe --user=mysql --datadir=/data &
    pid="$!"

    # Attendi che il database sia pronto
    RETRIES=30
    until mysqladmin ping --silent || [ $RETRIES -eq 0 ]; do
        sleep 1
        RETRIES=$((RETRIES - 1))
    done

    if [ $RETRIES -eq 0 ]; then
        echo "[ERROR] Impossibile avviare MariaDB durante l'inizializzazione!"
        exit 1
    fi

    # ==========================================
    # 2. Creazione del Database, Utente e Privilegi
    # (Lo schema delle tabelle verrà creato da PHP via Migrator)
    # ==========================================
    echo "[INFO] Creazione database '$DB_NAME' e utente '$DB_USER'..."
    mysql -e "
        CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
        CREATE USER IF NOT EXISTS '$DB_USER'@'127.0.0.1' IDENTIFIED BY '$DB_PASS';
        GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'localhost';
        GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'127.0.0.1';
        FLUSH PRIVILEGES;
    "

    echo "[INFO] Inizializzazione DB completata con successo."

    # Spegne il MariaDB temporaneo
    mysqladmin -u root shutdown
    wait "$pid"
fi

# ==========================================
# 3. Avvia MariaDB definitivo in background
# ==========================================
echo "[INFO] Avvio di MariaDB..."
mysqld_safe --user=mysql --datadir=/data &

RETRIES=15
until mysqladmin ping --silent || [ $RETRIES -eq 0 ]; do
    sleep 1
    RETRIES=$((RETRIES - 1))
done

# ==========================================
# 4. Avvia Apache in primo piano
# ==========================================
echo "[INFO] Avvio di Apache web server..."
exec httpd -D FOREGROUND