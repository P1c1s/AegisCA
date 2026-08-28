<?php
// src/Classes/Certificate.php

class Certificate {
    private ?PDO $pdo;
    private ?int $id;
    private ?int $ca_id;
    private string $common_name;
    private ?string $subject_country;
    private ?string $subject_state;
    private ?string $subject_locality;
    private ?string $subject_organization;
    private ?string $subject_org_unit;
    private string $key_type;
    private int $key_bits;
    private ?string $description;
    private string $status;
    private string $valid_from;
    private string $valid_to;
    private ?string $cert_data;
    private ?string $key_data;
    private ?string $created_at;

    public function __construct(
        ?PDO $pdo,
        ?int $id,
        ?int $ca_id,
        string $common_name,
        ?string $subject_country,
        ?string $subject_state,
        ?string $subject_locality,
        ?string $subject_organization,
        ?string $subject_org_unit,
        string $key_type,
        int $key_bits,
        ?string $description,
        string $status,
        string $valid_from,
        string $valid_to,
        ?string $cert_data,
        ?string $created_at = null,
        ?string $key_data = null
    ) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->ca_id = $ca_id;
        $this->common_name = $common_name;
        $this->subject_country = $subject_country;
        $this->subject_state = $subject_state;
        $this->subject_locality = $subject_locality;
        $this->subject_organization = $subject_organization;
        $this->subject_org_unit = $subject_org_unit;
        $this->key_type = $key_type;
        $this->key_bits = $key_bits;
        $this->description = $description;
        $this->status = $status;
        $this->valid_from = $valid_from;
        $this->valid_to = $valid_to;
        $this->cert_data = $cert_data;
        $this->created_at = $created_at;
        $this->key_data = $key_data;
    }

    /**
     * Recupera tutti i certificati ordinati per data di creazione.
     * @return self[]
     */
    public static function findAll(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM certificates ORDER BY created_at DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $certificates = [];
        foreach ($rows as $data) {
            $certificates[] = new self(
                $pdo,
                (int)$data['id'],
                isset($data['ca_id']) ? (int)$data['ca_id'] : null,
                $data['common_name'],
                $data['subject_country'] ?? null,
                $data['subject_state'] ?? null,
                $data['subject_locality'] ?? null,
                $data['subject_organization'] ?? null,
                $data['subject_org_unit'] ?? null,
                $data['key_type'] ?? 'rsa',
                (int)($data['key_bits'] ?? 2048),
                $data['description'] ?? null,
                $data['status'] ?? 'active',
                $data['valid_from'] ?? '',
                $data['valid_to'] ?? '',
                $data['cert_data'] ?? null,
                $data['created_at'] ?? '',
                $data['key_data'] ?? null
            );
        }
        return $certificates;
    }

    /**
     * Trova un certificato tramite il suo ID.
     */
    public static function findById(PDO $pdo, int $id): ?self {
        $stmt = $pdo->prepare("SELECT * FROM certificates WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;

        return new self(
            $pdo,
            (int)$data['id'],
            isset($data['ca_id']) ? (int)$data['ca_id'] : null,
            $data['common_name'],
            $data['subject_country'] ?? null,
            $data['subject_state'] ?? null,
            $data['subject_locality'] ?? null,
            $data['subject_organization'] ?? null,
            $data['subject_org_unit'] ?? null,
            $data['key_type'] ?? 'rsa',
            (int)($data['key_bits'] ?? 2048),
            $data['description'] ?? null,
            $data['status'] ?? 'active',
            $data['valid_from'] ?? '',
            $data['valid_to'] ?? '',
            $data['cert_data'] ?? null,
            $data['created_at'] ?? '',
            $data['key_data'] ?? null
        );
    }

    /**
     * Trova tutti i certificati emessi da una specifica CA.
     * @return self[]
     */
    public static function findByCaId(PDO $pdo, int $caId): array {
        $stmt = $pdo->prepare("SELECT * FROM certificates WHERE ca_id = :ca_id ORDER BY created_at DESC");
        $stmt->execute(['ca_id' => $caId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $certificates = [];
        foreach ($rows as $data) {
            $certificates[] = new self(
                $pdo,
                (int)$data['id'],
                (int)$data['ca_id'],
                $data['common_name'],
                $data['subject_country'] ?? null,
                $data['subject_state'] ?? null,
                $data['subject_locality'] ?? null,
                $data['subject_organization'] ?? null,
                $data['subject_org_unit'] ?? null,
                $data['key_type'] ?? 'rsa',
                (int)($data['key_bits'] ?? 2048),
                $data['description'] ?? null,
                $data['status'] ?? 'active',
                $data['valid_from'] ?? '',
                $data['valid_to'] ?? '',
                $data['cert_data'] ?? null,
                $data['created_at'] ?? '',
                $data['key_data'] ?? null
            );
        }
        return $certificates;
    }

    /**
     * Salva un nuovo certificato nel database.
     */
    public function save(): bool {
        if (!$this->pdo) {
            throw new Exception("Connessione PDO non disponibile.");
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO certificates (
                ca_id, common_name, subject_country, subject_state, subject_locality, 
                subject_organization, subject_org_unit, key_type, key_bits, 
                description, status, valid_from, valid_to, cert_data, key_data
            ) VALUES (
                :ca_id, :common_name, :subject_country, :subject_state, :subject_locality, 
                :subject_organization, :subject_org_unit, :key_type, :key_bits, 
                :description, :status, :valid_from, :valid_to, :cert_data, :key_data
            )
        ");

        $success = $stmt->execute([
            'ca_id'                   => $this->ca_id,
            'common_name'             => $this->common_name,
            'subject_country'         => $this->subject_country,
            'subject_state'           => $this->subject_state,
            'subject_locality'        => $this->subject_locality,
            'subject_organization'    => $this->subject_organization,
            'subject_org_unit'        => $this->subject_org_unit,
            'key_type'                => $this->key_type,
            'key_bits'                => $this->key_bits,
            'description'             => $this->description,
            'status'                  => $this->status,
            'valid_from'              => $this->valid_from,
            'valid_to'                => $this->valid_to,
            'cert_data'               => $this->cert_data,
            'key_data'                => $this->key_data ?? ''
        ]);

        if ($success) {
            $this->id = (int)$this->pdo->lastInsertId();
        }

        return $success;
    }

    public function setStatus(string $status): bool {
        if (!$this->pdo || !$this->id) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE certificates SET status = :status WHERE id = :id");
        $success = $stmt->execute([
            'status' => $status,
            'id'     => $this->id
        ]);

        if ($success) {
            $this->status = $status;
        }

        return $success;
    }

    public function delete(): bool {
        if (!$this->pdo || !$this->id) {
            return false;
        }
        $stmt = $this->pdo->prepare("DELETE FROM certificates WHERE id = ?");
        $success = $stmt->execute([$this->id]);

        if ($success) {
            $this->id = 0;
            $this->common_name = '';
            $this->cert_data = null;
            $this->key_data = null;
        }

        return $success;
    }

    public function getFingerprint(string $algo = 'sha256'): string {
        $pemCert = $this->cert_data ?? '';
        if (empty(trim($pemCert))) return '-';
        
        $algo = strtolower($algo);
        $fp = @openssl_x509_fingerprint($pemCert, $algo, false);
        if ($fp !== false && !empty($fp)) {
            return strtoupper(implode(':', str_split($fp, 2)));
        }
        
        try {
            $cleanPem = preg_replace('/-----BEGIN CERTIFICATE-----|-----END CERTIFICATE-----|\s+/', '', $pemCert);
            $derBin = base64_decode($cleanPem);
            if (!$derBin) return '-';
            return strtoupper(implode(':', str_split(hash($algo, $derBin), 2)));
        } catch (Exception $e) {
            return '-';
        }
    }

    private function getSecureCertData(): ?string {
        if (!class_exists('Auth') || !Auth::isLoggedIn()) {
            throw new Exception("Accesso negato: è richiesta l'autenticazione per accedere al certificato.");
        }
        return $this->cert_data;
    }

    private function getSecureKeyData(): ?string {
        if (!class_exists('Auth') || !Auth::isLoggedIn()) {
            throw new Exception("Accesso negato: è richiesta l'autenticazione per accedere alla chiave privata.");
        }
        return $this->key_data;
    }

    public function isExpired(): bool {
        return strtotime($this->valid_to) < time();
    }

    public function isRevoked(): bool {
        return $this->status === 'revoked';
    }

    public function getId(): ?int { return $this->id; }
    public function getCaId(): ?int { return $this->ca_id; }
    public function getCommonName(): string { return $this->common_name; }
    public function getSubjectCountry(): ?string { return $this->subject_country; }
    public function getSubjectState(): ?string { return $this->subject_state; }
    public function getSubjectLocality(): ?string { return $this->subject_locality; }
    public function getSubjectOrganization(): ?string { return $this->subject_organization; }
    public function getSubjectOrgUnit(): ?string { return $this->subject_org_unit; }
    public function getKeyType(): string { return $this->key_type; }
    public function getKeyBits(): int { return $this->key_bits; }
    public function getDescription(): ?string { return $this->description; }
    public function getStatus(): string { return $this->status; }
    public function getValidFrom(): string { return $this->valid_from; }
    public function getValidTo(): string { return $this->valid_to; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function getCertData(): ?string { return $this->getSecureCertData(); }
    public function getKeyData(): ?string { return $this->getSecureKeyData(); }
}