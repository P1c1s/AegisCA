#!/usr/bin/env python3
import argparse
import sys
import os
import mysql.connector
import bcrypt
from tabulate import tabulate
from pathlib import Path

# Per leggere le vere date e CN dai certificati X.509
try:
    from cryptography import x509
    from cryptography.hazmat.backends import default_backend
    HAS_CRYPTO = True
except ImportError:
    HAS_CRYPTO = False

# Percorso assoluto per l'ambiente Docker/produzione
ABS_VERSION_FILE = Path("/var/www/localhost/htdocs/config/VERSION")

# Percorso relativo di fallback per lo sviluppo locale
LOCAL_VERSION_FILE = Path(__file__).resolve().parent.parent / "VERSION"

if ABS_VERSION_FILE.exists():
    APP_VERSION = ABS_VERSION_FILE.read_text().strip()
elif LOCAL_VERSION_FILE.exists():
    APP_VERSION = LOCAL_VERSION_FILE.read_text().strip()
else:
    APP_VERSION = "30.03.2021"

# --- CONFIGURAZIONE DINAMICA VIA ENVIRONMENT ---
DB_CONFIG = {
    "user": os.getenv("DB_USER", "athena"),
    "password": os.getenv("DB_PASS", "goat-snake-gorgon"),
    "database": os.getenv("DB_NAME", "aegis_ca"),
    "host": os.getenv("DB_HOST", "127.0.0.1"),
    "port": int(os.getenv("DB_PORT", 3306))
}

# --- UI & COLORI INTERFACCIA ---
class UI:
    CYAN = "\033[96m"
    GREEN = "\033[92m"
    YELLOW = "\033[93m"
    RED = "\033[91m"
    BOLD = "\033[1m"
    END = "\033[0m"

    @staticmethod
    def header(text): print(f"\n{UI.CYAN}{UI.BOLD}>>> {text.upper()}{UI.END}")
    @staticmethod
    def success(text): print(f"{UI.GREEN}  ✔ {text}{UI.END}")
    @staticmethod
    def error(text): print(f"{UI.RED}  ✘ ERRORE: {text}{UI.END}", file=sys.stderr)

# --- FORMATTER PER HELP COLORATO ---
class ColorHelpFormatter(argparse.RawTextHelpFormatter):
    def _format_action(self, action):
        parts = super()._format_action(action)
        if action.option_strings:
            for option in action.option_strings:
                parts = parts.replace(option, f"{UI.GREEN}{option}{UI.END}")
        elif action.choices:
            for choice in action.choices:
                parts = parts.replace(choice, f"{UI.GREEN}{choice}{UI.END}")
        elif action.dest and action.dest != "help":
            parts = parts.replace(action.dest, f"{UI.GREEN}{action.dest}{UI.END}")
        return parts

    def start_section(self, heading):
        colored_heading = f"{UI.CYAN}{UI.BOLD}{heading}{UI.END}"
        super().start_section(colored_heading)

# --- DATABASE MANAGER ---
class AegisDB:
    def __init__(self):
        self.conn = None
        socket_paths = ['/var/run/mysqld/mysqld.sock', '/var/lib/mysql/mysql.sock', '/tmp/mysql.sock', '/run/mysqld/mysqld.sock']
        
        # Prova prima via socket locale
        for sock in socket_paths:
            if os.path.exists(sock):
                try:
                    self.conn = mysql.connector.connect(unix_socket=sock, **{k:v for k,v in DB_CONFIG.items() if k != 'host'})
                    return
                except mysql.connector.Error: 
                    continue
        
        # In alternativa prova via TCP/IP
        try:
            self.conn = mysql.connector.connect(**DB_CONFIG)
        except mysql.connector.Error as e:
            UI.error(f"Connessione DB fallita: {e}")
            sys.exit(1)
    
    def execute(self, query, params=None):
        cursor = self.conn.cursor(dictionary=True)
        cursor.execute(query, params or ())
        result = cursor.fetchall() if cursor.with_rows else None
        self.conn.commit()
        cursor.close()
        return result

    def close(self): 
        if self.conn and self.conn.is_connected():
            self.conn.close()

# --- HELPER PER PARSING CERTIFICATI ---
def parse_cert_info(cert_pem):
    """Estrae Common Name e date di validità dal PEM del certificato."""
    if not HAS_CRYPTO:
        return None, None, None
    try:
        cert = x509.load_pem_x509_certificate(cert_pem.encode(), default_backend())
        cn_attributes = cert.subject.get_attributes_for_oid(x509.NameOID.COMMON_NAME)
        cn = cn_attributes[0].value if cn_attributes else "Unknown"
        
        # Gestione compatibilità datetime (cryptography >= 42.0 usa naive UTC)
        try:
            valid_from = cert.not_valid_before_utc
            valid_to = cert.not_valid_after_utc
        except AttributeError:
            valid_from = cert.not_valid_before
            valid_to = cert.not_valid_after

        return cn, valid_from, valid_to
    except Exception:
        return None, None, None

# --- LOGICA DEI COMANDI ---
class UserManager:
    @staticmethod
    def run(db, args):
        if args.action == 'list':
            rows = db.execute("SELECT id, username, created_at FROM users")
            UI.header("Gestione Utenti")
            print(tabulate([[r['id'], r['username'], r['created_at']] for r in rows], headers=["ID", "Username", "Data Creazione"], tablefmt="simple"))
        
        elif args.action == 'create':
            if not args.username or not args.password:
                UI.error("Parametri obbligatori mancanti: usa -u [user] -p [pass]")
                return
            hashed = bcrypt.hashpw(args.password.encode(), bcrypt.gensalt()).decode()
            db.execute("INSERT INTO users (username, password) VALUES (%s, %s)", (args.username, hashed))
            UI.success(f"Utente '{args.username}' registrato nel sistema.")
            
        elif args.action == 'update':
            if not args.username:
                UI.error("Specificare l'utente da modificare con -u [username_attuale]")
                return
            
            user_exists = db.execute("SELECT id FROM users WHERE username = %s", (args.username,))
            if not user_exists:
                UI.error(f"L'utente '{args.username}' non esiste nel database.")
                return

            if args.new_password:
                hashed = bcrypt.hashpw(args.new_password.encode(), bcrypt.gensalt()).decode()
                db.execute("UPDATE users SET password = %s WHERE username = %s", (hashed, args.username))
                UI.success(f"Password aggiornata con successo per l'utente '{args.username}'.")

            if args.new_username:
                try:
                    db.execute("UPDATE users SET username = %s WHERE username = %s", (args.new_username, args.username))
                    UI.success(f"Username modificato da '{args.username}' a '{args.new_username}'.")
                except mysql.connector.Error as e:
                    UI.error(f"Impossibile rinominare l'utente: {e}")

            if not args.new_password and not args.new_username:
                UI.error("Nessun dato di aggiornamento fornito. Usa --new-username o --new-password.")

class CertManager:
    @staticmethod
    def run(db, args):
        if args.action == 'list':
            if not args.type:
                UI.error("Specificare il tipo con -t [ca|cert]")
                return
            table = "cas" if args.type == 'ca' else "certificates"
            rows = db.execute(f"SELECT id, common_name, valid_from, valid_to FROM `{table}`")
            UI.header(f"Elenco {table.upper()}")
            print(tabulate([[r['id'], r['common_name'], r['valid_from'], r['valid_to']] for r in rows], 
                           headers=["ID", "Common Name", "Valido Dal", "Valido Al"], tablefmt="simple"))

        elif args.action == 'import':
            if not args.file or not args.type:
                UI.error("Specificare il nome base o wildcard con -f e il tipo con -t [ca|cert]")
                return
            
            base_names = set()
            for filepath in args.file:
                if os.path.isdir(filepath):
                    continue
                
                if filepath.endswith('.crt') or filepath.endswith('.key'):
                    base_names.add(filepath[:-4])
                else:
                    base_names.add(filepath)

            if not base_names:
                UI.error("Nessun file valido trovato per l'importazione.")
                return

            for base_name in base_names:
                crt_path = f"{base_name}.crt"
                key_path = f"{base_name}.key"
                fallback_cn = os.path.basename(base_name)

                if not os.path.exists(crt_path) or not os.path.exists(key_path):
                    UI.error(f"Coppia incompleta per '{fallback_cn}'. Assicurati che entrambi i file ({crt_path}, {key_path}) esistano.")
                    continue
                
                with open(crt_path, 'r') as f_crt: cert_content = f_crt.read()
                with open(key_path, 'r') as f_key: key_content = f_key.read()

                # Tenta l'estrazione delle informazioni reali dal certificato
                parsed_cn, valid_from, valid_to = parse_cert_info(cert_content)
                common_name = parsed_cn if parsed_cn else fallback_cn

                try:
                    if args.type == 'ca':
                        if valid_from and valid_to:
                            db.execute("""
                                INSERT INTO cas (common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, cert_data, key_data, key_bits, valid_from, valid_to) 
                                VALUES (%s, 'IT', 'RM', 'Roma', 'Aegis', 'CA', %s, %s, 2048, %s, %s)
                            """, (common_name, cert_content, key_content, valid_from, valid_to))
                        else:
                            db.execute("""
                                INSERT INTO cas (common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, cert_data, key_data, key_bits, valid_from, valid_to) 
                                VALUES (%s, 'IT', 'RM', 'Roma', 'Aegis', 'CA', %s, %s, 2048, NOW(), NOW() + INTERVAL 1 YEAR)
                            """, (common_name, cert_content, key_content))
                        UI.success(f"CA '{common_name}' importata con successo.")
                    else:
                        ca_id = args.ca_id if hasattr(args, 'ca_id') and args.ca_id else 1
                        if valid_from and valid_to:
                            db.execute("""
                                INSERT INTO certificates (ca_id, common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, cert_data, key_data, key_bits, valid_from, valid_to) 
                                VALUES (%s, %s, 'IT', 'RM', 'Roma', 'Aegis', 'Node', %s, %s, 2048, %s, %s)
                            """, (ca_id, common_name, cert_content, key_content, valid_from, valid_to))
                        else:
                            db.execute("""
                                INSERT INTO certificates (ca_id, common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, cert_data, key_data, key_bits, valid_from, valid_to) 
                                VALUES (%s, %s, 'IT', 'RM', 'Roma', 'Aegis', 'Node', %s, %s, 2048, NOW(), NOW() + INTERVAL 1 YEAR)
                            """, (ca_id, common_name, cert_content, key_content))
                        UI.success(f"Certificato '{common_name}' importato con successo.")
                except mysql.connector.Error as e:
                    UI.error(f"Errore DB durante l'importazione di '{common_name}': {e}")

        elif args.action == 'export':
            if not args.type:
                UI.error("Specificare il tipo con -t [ca|cert]")
                return
            
            table = "cas" if args.type == 'ca' else "certificates"
            
            query = f"SELECT common_name, cert_data, key_data FROM `{table}`"
            params = ()

            if args.cn and args.cn.lower() != 'all':
                query += " WHERE common_name = %s"
                params = (args.cn,)
                rows = db.execute(query, params)
                
                if rows:
                    clean_name = args.cn.replace('*', 'wildcard')
                    with open(f"{clean_name}.crt", "w") as f: f.write(rows[0]['cert_data'])
                    with open(f"{clean_name}.key", "w") as f: f.write(rows[0]['key_data'])
                    UI.success(f"File esportati correttamente: {clean_name}.crt e {clean_name}.key")
                else:
                    UI.error(f"Nessun elemento trovato per '{args.cn}' nella tabella '{args.type}'")
            
            else:
                UI.header(f"Esportazione di massa: {table.upper()}")
                rows = db.execute(query)
                
                if not rows:
                    UI.error(f"Nessun dato trovato nella tabella '{table}' per l'esportazione di massa.")
                    return
                
                export_dir = f"exported_{table}"
                os.makedirs(export_dir, exist_ok=True)
                
                count = 0
                for row in rows:
                    clean_name = row['common_name'].replace('*', 'wildcard')
                    path_crt = os.path.join(export_dir, f"{clean_name}.crt")
                    path_key = os.path.join(export_dir, f"{clean_name}.key")
                    
                    with open(path_crt, "w") as f: f.write(row['cert_data'])
                    with open(path_key, "w") as f: f.write(row['key_data'])
                    count += 1
                
                UI.success(f"Esportati con successo {count} certificati e chiavi nella cartella '{export_dir}/'")

# --- CORE PARSER ---
def main():
    parser = argparse.ArgumentParser(
        description=f"{UI.YELLOW}Aegis-CA: A modern cryptographic manager designed to store, manage and rotate CA and SSL certificates.{UI.END}",
        formatter_class=ColorHelpFormatter,
        add_help=False
    )

    parser.add_argument("-h", "--help", action="help", help="show this help message and exit")
    parser.add_argument("-v", "--verbose", action="store_true", help="set verbosity level")
    parser.add_argument("-V", "--version", action="version", version=f"%(prog)s {APP_VERSION}", help="show version")

    sub = parser.add_subparsers(dest="command", metavar="COMMAND")

    # Sotto-comando: user
    p_user = sub.add_parser('user', help="Gestione delle credenziali e degli utenti amministrativi", formatter_class=ColorHelpFormatter)
    p_user.add_argument('action', choices=['list', 'create', 'update'], help="Azione utente da eseguire")
    p_user.add_argument('-u', '--username', metavar='USER', help="Username attuale dell'utente (richiesto per create e update)")
    p_user.add_argument('-p', '--password', metavar='PASS', help="Password in chiaro (richiesto solo per create)")
    p_user.add_argument('--new-username', metavar='NEW_USER', help="Nuovo username da assegnare (solo per update)")
    p_user.add_argument('--new-password', metavar='NEW_PASS', help="Nuova password in chiaro da crittografare (solo per update)")
    p_user.set_defaults(handler=UserManager)

    # Sotto-comando: cert
    p_cert = sub.add_parser('cert', help="Gestione dei file, importazione ed esportazione massiva delle CA/Certificati", formatter_class=ColorHelpFormatter)
    p_cert.add_argument('action', choices=['list', 'import', 'export'], help="Azione sui file crittografici")
    p_cert.add_argument('-t', '--type', choices=['ca', 'cert'], help="Specifica se l'operazione è su una CA o su un certificato foglia")
    p_cert.add_argument('-f', '--file', nargs='+', metavar='PATH', help="Nome base del file o wildcard (es. cartella/*)")
    p_cert.add_argument('--ca-id', type=int, default=1, help="ID della CA associata (richiesto solo se type=cert, default: 1)")
    p_cert.add_argument('--cn', metavar='NAME', help="Common Name (CN) specifico da esportare. Omettere o usare 'all' per esportazione di massa")
    p_cert.set_defaults(handler=CertManager)

    args = parser.parse_args()

    if not args.command:
        parser.print_help()
        sys.exit(0)

    db = AegisDB()
    try:
        args.handler.run(db, args)
    except Exception as e:
        UI.error(f"Errore di esecuzione: {e}")
    finally:
        db.close()

if __name__ == "__main__":
    main()