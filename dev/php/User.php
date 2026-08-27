<?php

class User {
    private int $id;
    private string $username;
    private string $password;
    private string $default_lang;
    private DateTimeImmutable $created_at;

    public function __construct(int $id, string $username, string $password, string $default_lang, DateTimeImmutable $created_at) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->default_lang = $default_lang;
        $this->created_at = $created_at;
    }

    /**
     * Tenta il login con username e password.
     * Restituisce l'oggetto User se le credenziali sono corrette, altrimenti null.
     */
    public static function login(PDO $pdo, string $username, string $passwordInput): ?self {
        // Selezioniamo anche la password dal database
        $stmt = $pdo->prepare("SELECT id, username, password, default_lang, created_at FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Se l'utente non esiste
        if (!$data) {
            return null;
        }

        // Verifica la password inserita con l'hash salvato nel DB
        if (!password_verify($passwordInput, $data['password'])) {
            return null; // Password errata
        }

        // Credenziali corrette: restituisce l'istanza dell'utente
        return new self(
            id: (int)$data['id'],
            username: $data['username'],
            password: $data['password'],
            default_lang: $data['default_lang'],
            created_at: new DateTimeImmutable($data['created_at'])
        );
    }

    /**
     * Trova un utente per ID
     */
    public static function findById(PDO $pdo, int $id): ?self {
        $stmt = $pdo->prepare("SELECT id, username, password, default_lang, created_at FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;

        return new self(
            id: (int)$data['id'],
            username: $data['username'],
            password: $data['password'],
            default_lang: $data['default_lang'],
            created_at: new DateTimeImmutable($data['created_at'])
        );
    }

    // --- Getter ---
    public function getId(): int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getDefaultLang(): string { return $this->default_lang; }
    public function getCreatedAt(): DateTimeImmutable { return $this->created_at; }
}

?>