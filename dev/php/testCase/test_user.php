<?php

require_once 'config.php';
require_once '../User.php';

echo "--- 1. Test Registrazione (User::signup) ---\n";
$username = "testuser_" . rand(1000, 9999);
$password = "SecurePass123!";

$user = User::signup($pdo, $username, $password, 'it');
if ($user) {
    echo "Utente creato con successo! ID: {$user->getId()}, Username: {$user->getUsername()}\n\n";
} else {
    echo "Errore nella registrazione dell'utente.\n";
    exit;
}

$userId = $user->getId();

echo "--- 2. Test Ricerca (findById e findByUsername) ---\n";
$foundById = User::findById($pdo, $userId);
$foundByName = User::findByUsername($pdo, $username);

if ($foundById && $foundByName) {
    echo "Utente " . $username . "trovato correttamente tramite ID e Username!\n\n";
} else {
    echo "Errore nella ricerca dell'utente.\n";
}

echo "--- 3. Test Verifica Password (verifyPassword) ---\n";
if ($user->verifyPassword($password)) {
    echo "Password verificata con successo!\n\n";
} else {
    echo "Errore: verifica password fallita.\n\n";
}

echo "--- 4. Test Modifiche Profilo (setDefaultLang e setPassword) ---\n";
$user->setDefaultLang('en');
echo "Lingua aggiornata a: {$user->getDefaultLang()}\n";

$user->setPassword('NewSecurePass456!');
if ($user->verifyPassword('NewSecurePass456!')) {
    echo "Password modificata e verificata con successo!\n\n";
} else {
    echo "Errore nella modifica della password.\n\n";
}

echo "--- 5. Pulizia (Eliminazione utente di test) ---\n";
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$userId]);
echo "Utente di test rimosso dal database.\n";