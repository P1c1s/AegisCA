<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DB_HOST', 'localhost');
define('DB_USER', 'lorenzo');
define('DB_PASS', 'qss-s3E-IH9_Khz');
define('DB_NAME', 'ssl_manager');

define('ROOT_PATH', __DIR__ . '/../');
define('BASE_URL', '172.17.0.2');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
