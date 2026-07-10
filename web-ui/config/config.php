<?php
// config/config.php

// 1. Monitoraggio errori (tienilo attivo in sviluppo, disattivalo in produzione!)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Credenziali Database
define('DB_HOST', 'localhost');
define('DB_USER', 'lorenzo');
define('DB_PASS', 'qss-s3E-IH9_Khz');
define('DB_NAME', 'ssl_manager');

// 3. Percorsi globali aggiornati e dinamici
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '/');
}

// Rilevamento automatico del protocollo (HTTP o HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

// Rilevamento automatico dell'host corrente (IP Docker, IP di rete locale o dominio + eventuale porta)
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
    // In produzione sarebbe meglio fare un log dell'errore invece di mostrarlo a schermo
    die("Errore critico di connessione al database."); 
}

// 5. Inizializzazione Sessione globale
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}