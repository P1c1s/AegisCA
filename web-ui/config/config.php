<?php
// config/config.php

// Legge la versione dal file VERSION nella radice del progetto
$version_file = __DIR__ . '/VERSION';
define('APP_VERSION', file_exists($version_file) ? trim(file_get_contents($version_file)) : '0.0.0');


// 1. Monitoraggio errori (tienilo attivo in sviluppo, disattivalo in produzione!)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Credenziali Database
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'athena');
define('DB_PASS', getenv('DB_PASS') ?: 'goat-snake-gorgon');
define('DB_NAME', getenv('DB_NAME') ?: 'aegis_ca');



// 3. Percorsi globali
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '/');
}

// Rilevamento automatico del protocollo (HTTP o HTTPS)
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') == 443;
$protocol = $isSecure ? "https://" : "http://";

// Rilevamento automatico dell'host corrente
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Generazione della BASE_URL dinamica
define('BASE_URL', $protocol . $host . '/');

// 4. Connessione sicura tramite PDO
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // Sostituisci temporaneamente la riga con questa per vedere il dettaglio:
    die("Errore dettagliato DB: " . $e->getMessage()); 
}

// 5. Inizializzazione Sessione e Impostazioni di Sicurezza Cookie
if (session_status() === PHP_SESSION_NONE) {
    // Configurazione cookie di sessione per mitigare XSS e CSRF a livello di browser
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isSecure, // True se sei in HTTPS
        'httponly' => true,      // Impedisce l'accesso al cookie via JavaScript (XSS)
        'samesite' => 'Lax'      // Protezione aggiuntiva nativa contro CSRF
    ]);
    session_start();
}

// 6. Helper Globali per la Protezione CSRF
/**
 * Restituisce o genera il token CSRF corrente per la sessione.
 */
function getCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifica se il token inviato corrisponde a quello della sessione.
 */
function verifyCsrfToken(?string $token): bool {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// 7. Inizializzazione Sistema Multilingua
$lang_init_path = ROOT_PATH . 'src/lang_init.php';
if (file_exists($lang_init_path)) {
    require_once $lang_init_path;
}