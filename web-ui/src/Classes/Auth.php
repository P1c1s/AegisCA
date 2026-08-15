<?php
// src/Classes/Auth.php

class Auth {
    public static function login($username, $password) {
        global $pdo; // $pdo arriva dal config.php caricato nell'index
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
        return false;
    }

    public static function signup($username, $password) {
        global $pdo;
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            return $stmt->execute([$username, $hashedPassword]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function changePassword($userId, $newPassword) {
        global $pdo;
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $userId]);
    }

    // MODIFICATO: Ora restituisce un booleano invece di fare il redirect fisso
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}