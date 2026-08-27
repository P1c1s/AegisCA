<?php

require_once 'User.php';

$pdo = new PDO("mysql:host=localhost;dbname=aegis_ca", "athena", "goat-snake-gorgon", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$user = User::signup($pdo, "lorenzo", "password", "en");

// Test login
$user = User::login($pdo, 'lorenzo', 'password');

if ($user) {
    echo "Login OK: {$user->getUsername()} (ID: {$user->getId()})\n";
} else {
    echo "Login Fallito\n";
}
