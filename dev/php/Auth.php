<?php
// src/Classes/Auth.php

class Auth {

    public static function login(PDO $pdo, string $username, string $password): bool {
        // Usa il Model User per trovare l'utente
        $user = User::findByUsername($pdo, $username);

        // Verifica la password tramite il metodo dedicato in User
        if ($user && $user->verifyPassword($password)) {
            if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
                session_start();
            }
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['lang'] = $user->getDefaultLang(); 
            return true;
        }
        return false;
    }

    public static function logout(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_destroy();
        } elseif (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
            $_SESSION = [];
            @session_destroy();
        } else {
            // Se gli header sono già partiti, pulisce comunque l'array di sessione in memoria
            $_SESSION = [];
        }
    }

    public static function isLoggedIn(): bool {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}