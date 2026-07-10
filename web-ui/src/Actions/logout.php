<?php
// src/Actions/logout.php
// Nota: Questo file viene caricato dal PageController quando l'utente richiede il logout.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Azzera l'array di sessione
$_SESSION = [];

// Distrugge la sessione sul server
session_destroy();

// Reindirizza alla pagina di login tramite il Front Controller centrale
header('Location: index.php?page=login');
exit;