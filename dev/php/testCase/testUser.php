<?php

require_once 'config.php';
require_once '../User.php';

$user = User::signup($pdo, "lorenzo", "password", "en");

// Test login
$user = User::login($pdo, 'lorenzo', 'password');

if ($user) {
    echo "Login OK: {$user->getUsername()} (ID: {$user->getId()})\n";
} else {
    echo "Login Fallito\n";
}
