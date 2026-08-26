#!/bin/sh
set -e

# ==========================================
# Configurazione Valori Default
# ==========================================
DB_NAME="${DB_NAME:-aegis_ca}"
DB_USER="${DB_USER:-athena}"
DB_PASS="${DB_PASS:-goat-snake-gorgon}"
APP_VERSION="${AEGIS_VERSION:-0.0.0}"

# Function per formattare i log in stile Pi-hole / FTL
log() {
    level="$1"
    msg="$2"
    ts=$(date -u +"%Y-%m-%d %H:%M:%S.000 UTC")
    pid=$$
    printf "%s [%dM] %s: %s\n" "$ts" "$pid" "$level" "$msg"
}

# ==========================================
# Banner di Avvio
# ==========================================
RED='\033[1;31m'
YELLOW='\033[38;5;220m'
WHITE='\033[1;37m'
NC='\033[0m' # No Color

VER_STR=$(printf "%-8s" "$APP_VERSION")

echo -e "${RED}*--------------------------------------------------------------------*${NC}"
echo -e "${RED}|${NC}                                                                    ${RED}|${NC}"
echo -e "${RED}|${NC}                             ${WHITE}AegisCA${NC}                                ${RED}|${NC}"
echo -e "${RED}|${NC}                           Version ${YELLOW}${VER_STR}${NC}                         ${RED}|${NC}"
echo -e "${RED}|--------------------------------------------------------------------|${NC}"
echo -e "${RED}|${NC} ${YELLOW}OpenSource Certificate Authority & Public Key Infrastructure (PKI)${NC} ${RED}|${NC}"
echo -e "${RED}|${NC} ${WHITE}Web Interface and Management Tool${NC}                                  ${RED}|${NC}"
echo -e "${RED}*--------------------------------------------------------------------*${NC}"
echo ""

# ==========================================
# Configurazione Timezone
# ==========================================
if [ -n "$TZ" ]; then
    log "INFO" "Configuring system timezone to $TZ"
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
    log "INFO" "First run detected. Initializing MariaDB storage in /data..."
    mysql_install_db --user=mysql --datadir=/data > /dev/null 2>&1

    # Avvia temporaneamente MariaDB in background per la configurazione iniziale
    mariadbd --user=mysql --datadir=/data > /dev/null 2>&1 &
    tmp_pid="$!"

    # Attesa avvio tramite controllo dell'esistenza del socket Unix
    RETRIES=30
    until [ -S /run/mysqld/mysqld.sock ] || [ $RETRIES -eq 0 ]; do
        sleep 1
        RETRIES=$((RETRIES - 1))
    done

    if [ $RETRIES -eq 0 ]; then
        log "ERROR" "Failed to start temporary MariaDB server during initialization!"
        exit 1
    fi

    # Creazione Database, Utente e Privilegi via script SQL passati a mariadbd/python3 o mariadb-admin
    log "INFO" "Creating database '$DB_NAME' and configuring user '$DB_USER'..."
    
    # Usiamo Python (già installato nell'immagine) con pymysql per eseguire la configurazione iniziale senza client CLI
    python3 -c "
import pymysql
conn = pymysql.connect(unix_socket='/run/mysqld/mysqld.sock', user='root', password='')
with conn.cursor() as cur:
    cur.execute(\"CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\")
    cur.execute(\"CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';\")
    cur.execute(\"CREATE USER IF NOT EXISTS '$DB_USER'@'127.0.0.1' IDENTIFIED BY '$DB_PASS';\")
    cur.execute(\"GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'localhost';\")
    cur.execute(\"GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'127.0.0.1';\")
    cur.execute(\"FLUSH PRIVILEGES;\")
conn.close()
"

    log "INFO" "Database successfully initialized"

    # Spegne il MariaDB temporaneo tramite kill del PID temporaneo
    kill -TERM "$tmp_pid" 2>/dev/null || true
    wait "$tmp_pid" 2>/dev/null || true
    sleep 2
fi

# ==========================================
# 2. Avvia MariaDB Silenzioso in Background
# ==========================================
mariadbd --user=mysql --datadir=/data > /dev/null 2>&1 &

# Attesa del socket Unix
RETRIES=15
until [ -S /run/mysqld/mysqld.sock ] || [ $RETRIES -eq 0 ]; do
    sleep 1
    RETRIES=$((RETRIES - 1))
done

# ==========================================
# 3. Rilevamento IP, Log Informativi e Avvio
# ==========================================
CONTAINER_IP=$(hostname -i 2>/dev/null | awk '{print $1}')
[ -z "$CONTAINER_IP" ] && CONTAINER_IP="127.0.0.1"

log "INFO" "MariaDB service is active and listening"
log "INFO" "Container IPv4 address: $CONTAINER_IP"
log "INFO" "http://$CONTAINER_IP:80 (HTTP, IPv4, OK)"
log "INFO" "AegisCA engine ready. Starting Apache web server..."

# 3 righe vuote prima dei log di Apache
echo ""
echo ""
echo ""

exec httpd -D FOREGROUND