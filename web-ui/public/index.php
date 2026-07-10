<?php
// public/index.php

// Definisce la radice del progetto risalendo di una cartella in modo condizionale
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '/');
}

require_once ROOT_PATH . 'config/config.php';
require_once ROOT_PATH . 'src/Classes/Auth.php';
require_once ROOT_PATH . 'src/Classes/PageController.php';

// Fai partire la sessione in sicurezza
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inizializza il Front Controller e gestisci la richiesta
$controller = new PageController();
$controller->handleRequest();