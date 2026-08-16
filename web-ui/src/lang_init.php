<?php
// lang_init.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Lingue supportate (inclusi fi, fr, de, pl, ro, es, pt, nl)
$GLOBALS['supported_langs'] = [
    'de', // Tedesco
    'en', // Inglese
    'es', // Spagnolo
    'fi', // Finlandese
    'fr', // Francese
    'it', // Italiano
    'nl', // Olandese
    'pl', // Polacco
    'pt', // Portoghese
    'ro', // Rumeno
];

$supported_langs = $GLOBALS['supported_langs'];

// 2. Cambio lingua via parametro URL (?lang=xx)
if (isset($_GET['lang']) && in_array($_GET['lang'], $supported_langs, true)) {
    $_SESSION['lang'] = $_GET['lang'];
}

// 3. Determina la lingua corrente
if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $supported_langs, true)) {
    $current_lang = $_SESSION['lang'];
} elseif (isset($_SESSION['user_default_lang']) && in_array($_SESSION['user_default_lang'], $supported_langs, true)) {
    $current_lang = $_SESSION['user_default_lang'];
    $_SESSION['lang'] = $current_lang;
} else {
    $current_lang = 'it'; // Imposta il default desiderato (es. 'it' o 'en')
}

// Alias per compatibilità con i template che usano $currentLang (camelCase)
$currentLang = $current_lang;

// 4. Caricamento file traduzione
// Se i file sono dentro "src/lang/", cambia la riga sotto in: __DIR__ . "/src/lang/{$current_lang}.php";
$lang_file = __DIR__ . "/lang/{$current_lang}.php";

if (file_exists($lang_file)) {
    $translations = require $lang_file;
} else {
    // Fallback se il file di lingua specifico non esiste
    $fallback_file = __DIR__ . "/lang/en.php";
    $translations = file_exists($fallback_file) ? require $fallback_file : [];
}

/**
 * Helper per tradurre le stringhe
 */
function __($key, $default = '') {
    global $translations;
    return $translations[$key] ?? ($default !== '' ? $default : $key);
}

/**
 * Helper per generare URL di cambio lingua
 */
function get_lang_url($lang) {
    $params = $_GET;
    $params['lang'] = $lang;
    return '?' . http_build_query($params);
}