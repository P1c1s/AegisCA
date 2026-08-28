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
     * Factory method per importare e salvare un Certificato foglia a partire da blocchi PEM.
     * 
     * @param PDO $pdo Istanza di connessione al DB
     * @param int|null $caId ID della CA emittente (se presente nel DB)
     * @param string $certContent Certificato X.509 in formato PEM
     * @param string|null $keyContent Chiave privata in formato PEM (opzionale)
     * @param string|null $description Descrizione opzionale
     * @return self Oggetto Certificate salvato e popolato con ID del DB
     * @throws Exception In caso di errore nella validazione OpenSSL o nel salvataggio
     */
    public static function importFromPem(
        PDO $pdo, 
        ?int $caId, 
        string $certContent, 
        ?string $keyContent = null, 
        ?string $description = null
    ): self {
        $parsed = @openssl_x509_parse($certContent);
        if (!$parsed) {
            throw new Exception("Certificato non valido o corrotto.");
        }

        $cn = $parsed['subject']['CN'] ?? 'Unknown Cert';
        $c  = $parsed['subject']['C'] ?? null;
        $st = $parsed['subject']['ST'] ?? null;
        $l  = $parsed['subject']['L'] ?? null;
        $o  = $parsed['subject']['O'] ?? null;
        $ou = $parsed['subject']['OU'] ?? null;

        $validFromTimestamp = $parsed['parsed']['validFrom_time_t'] ?? $parsed['validFrom_time_t'] ?? null;
        $validToTimestamp   = $parsed['parsed']['validTo_time_t']   ?? $parsed['validTo_time_t']   ?? null;

        $validFrom = $validFromTimestamp ? date('Y-m-d H:i:s', $validFromTimestamp) : date('Y-m-d H:i:s');
        $validTo   = $validToTimestamp   ? date('Y-m-d H:i:s', $validToTimestamp)   : date('Y-m-d H:i:s');

        $keyBits = 2048;
        $keyType = 'rsa';
        $pubKey  = @openssl_pkey_get_public($certContent);
        if ($pubKey) {
            $keyDetails = openssl_pkey_get_details($pubKey);
            if (isset($keyDetails['bits'])) {
                $keyBits = $keyDetails['bits'];
            }
            if (isset($keyDetails['type'])) {
                $keyType = match ($keyDetails['type']) {
                    OPENSSL_KEYTYPE_RSA => 'rsa',
                    OPENSSL_KEYTYPE_EC  => 'ec',
                    OPENSSL_KEYTYPE_DSA => 'dsa',
                    default             => 'rsa',
                };
            }
        }

        $cert = new self(
            pdo: $pdo,
            id: null,
            ca_id: $caId,
            common_name: $cn,
            subject_country: $c,
            subject_state: $st,
            subject_locality: $l,
            subject_organization: $o,
            subject_org_unit: $ou,
            key_type: $keyType,
            key_bits: $keyBits,
            description: $description ?? 'Certificato importato da file PEM',
            status: 'active',
            valid_from: $validFrom,
            valid_to: $validTo,
            cert_data: $certContent,
            created_at: null,
            key_data: $keyContent
        );

        if (!$cert->save()) {
            throw new Exception("Errore durante il salvataggio del certificato nel database.");
        }

        return $cert;
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
            $this->id = null;
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

    public static function countAll(PDO $pdo): int {
        return (int)$pdo->query("SELECT COUNT(*) FROM certificates")->fetchColumn();
    }

    public static function getExpiryStats(PDO $pdo): array {
        $stmt = $pdo->query("
            SELECT 
                SUM(CASE WHEN valid_to < NOW() THEN 1 ELSE 0 END) AS expired_certs,
                SUM(CASE WHEN valid_to >= NOW() THEN 1 ELSE 0 END) AS active_certs
            FROM certificates
        ");
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'expired' => (int)($stats['expired_certs'] ?? 0),
            'active'  => (int)($stats['active_certs'] ?? 0)
        ];
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