<?php
// src/Classes/User.php

class User {
    private ?PDO $pdo;
    private int $id;
    private string $username;
    private string $password;
    private string $default_lang;
    private DateTimeImmutable $created_at;

    public function __construct(?PDO $pdo, int $id, string $username, string $password, string $default_lang, DateTimeImmutable $created_at) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->default_lang = $default_lang;
        $this->created_at = $created_at;
    }

    public static function findById(PDO $pdo, int $id): ?self {
        $stmt = $pdo->prepare("SELECT id, username, password, default_lang, created_at FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;

        return new self($pdo, (int)$data['id'], $data['username'], $data['password'], $data['default_lang'], new DateTimeImmutable($data['created_at']));
    }

    public static function findByUsername(PDO $pdo, string $username): ?self {
        $stmt = $pdo->prepare("SELECT id, username, password, default_lang, created_at FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;

        return new self($pdo, (int)$data['id'], $data['username'], $data['password'], $data['default_lang'], new DateTimeImmutable($data['created_at']));
    }

    public static function signup(PDO $pdo, string $username, string $passwordInput, string $default_lang = 'it'): ?self {
        if (self::findByUsername($pdo, $username)) {
            return null; // Utente già esistente
        }

        $hashedPassword = password_hash($passwordInput, PASSWORD_BCRYPT);
        $createdAt = new DateTimeImmutable();
        
        $stmt = $pdo->prepare("INSERT INTO users (username, password, default_lang, created_at) VALUES (:username, :password, :default_lang, :created_at)");
        $stmt->execute([
            'username'     => $username,
            'password'     => $hashedPassword,
            'default_lang' => $default_lang,
            'created_at'   => $createdAt->format('Y-m-d H:i:s')
        ]);

        return new self($pdo, (int)$pdo->lastInsertId(), $username, $hashedPassword, $default_lang, $createdAt);
    }

    public function verifyPassword(string $passwordInput): bool {
        return password_verify($passwordInput, $this->password);
    }

    public function setDefaultLang(string $lang): void {
        $this->default_lang = $lang;
        $this->updateDatabase('default_lang', $lang);
    }

    public function setPassword(string $newPassword): void {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->password = $hashed;
        $this->updateDatabase('password', $hashed);
    }

    private function updateDatabase(string $field, mixed $value): void {
        if (!$this->pdo) throw new Exception("Connessione PDO non disponibile.");
        $stmt = $this->pdo->prepare("UPDATE users SET {$field} = :value WHERE id = :id");
        $stmt->execute(['value' => $value, 'id' => $this->id]);
    }

    public function delete(): bool {
        if (!$this->pdo) {
            throw new Exception("Connessione PDO non disponibile.");
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $state = $stmt->execute(['id' => $this->id]);
        $success = $stmt->execute(['id' => $this->id]);

        if ($success) {
            $this->id = 0;
            $this->username = 'None';
            $this->password = 'None';
        }

        return $success;
    }

    public function getId(): int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getDefaultLang(): string { return $this->default_lang; }
    public function getCreatedAt(): DateTimeImmutable { return $this->created_at; }
}