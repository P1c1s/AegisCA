<?php

require_once 'config.php';
require_once '../User.php';
require_once '../Auth.php';

// Avviamo la sessione per permettere ad Auth di lavorarci
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "--- 0. Preparazione utente di supporto per il test Auth ---\n";
$username = "authuser_" . rand(1000, 9999);
$password = "AuthPass123!";

$user = User::signup($pdo, $username, $password, 'it');
if (!$user) {
    echo "Impossibile creare l'utente di supporto.\n";
    exit;
}
echo "Utente di supporto creato con ID: {$user->getId()}\n\n";

echo "--- 1. Test Login con credenziali ERRATE ---\n";
$loginFailed = Auth::login($pdo, $username, "WrongPassword");
if (!$loginFailed && !Auth::isLoggedIn()) {
    echo "Test superato: login rifiutato correttamente con password errata.\n\n";
} else {
    echo "Errore: il login è andato a buon fine con password errata!\n\n";
}

echo "--- 2. Test Login con credenziali CORRETTE ---\n";
$loginSuccess = Auth::login($pdo, $username, $password);
if ($loginSuccess && Auth::isLoggedIn()) {
    echo "Login effettuato con successo!\n";
    echo "Dati salvati in sessione:\n";
    echo " - user_id: {$_SESSION['user_id']}\n";
    echo " - username: {$_SESSION['username']}\n";
    echo " - lang: {$_SESSION['lang']}\n\n";
} else {
    echo "Errore nel login con credenziali corrette.\n\n";
}

echo "--- 3. Test Logout ---\n";
Auth::logout();
if (!Auth::isLoggedIn() && empty($_SESSION['user_id'])) {
    echo "Logout eseguito con successo! Sessione pulita.\n\n";
} else {
    echo "Errore durante il logout.\n\n";
}

echo "--- 4. Pulizia (Eliminazione utente di supporto) ---\n";
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$user->getId()]);
echo "Utente di supporto rimosso dal database.\n";