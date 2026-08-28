<?php
// src/Classes/Ca.php

class Ca {
    private ?PDO $pdo;
    private ?int $id;
    private string $common_name;
    private ?string $subject_country;
    private ?string $subject_state;
    private ?string $subject_locality;
    private ?string $subject_organization;
    private ?string $subject_org_unit;
    private string $key_type;
    private int $key_bits;
    private ?string $crl_distribution_points;
    private ?string $ocsp_server;
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
        string $common_name,
        ?string $subject_country,
        ?string $subject_state,
        ?string $subject_locality,
        ?string $subject_organization,
        ?string $subject_org_unit,
        string $key_type,
        int $key_bits,
        ?string $crl_distribution_points,
        ?string $ocsp_server,
        ?string $description,
        string $status,
        string $valid_from,
        string $valid_to,
        ?string $cert_data,
        ?string $created_at,
        ?string $key_data = null
    ) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->common_name = $common_name;
        $this->subject_country = $subject_country;
        $this->subject_state = $subject_state;
        $this->subject_locality = $subject_locality;
        $this->subject_organization = $subject_organization;
        $this->subject_org_unit = $subject_org_unit;
        $this->key_type = $key_type;
        $this->key_bits = $key_bits;
        $this->crl_distribution_points = $crl_distribution_points;
        $this->ocsp_server = $ocsp_server;
        $this->description = $description;
        $this->status = $status;
        $this->valid_from = $valid_from;
        $this->valid_to = $valid_to;
        $this->cert_data = $cert_data;
        $this->created_at = $created_at;
        $this->key_data = $key_data;
    }

    /**
     * Factory method per importare e salvare una CA a partire da blocchi PEM.
     * 
     * @param PDO $pdo Istanza di connessione al DB
     * @param string $certContent Certificato X.509 in formato PEM
     * @param string|null $keyContent Chiave privata in formato PEM (opzionale)
     * @param string|null $description Descrizione opzionale
     * @return self Oggetto Ca salvato e popolato con ID del DB
     * @throws Exception In caso di errore nella validazione OpenSSL o nel salvataggio
     */
    public static function importFromPem(PDO $pdo, string $certContent, ?string $keyContent = null, ?string $description = null): self {
        $parsed = @openssl_x509_parse($certContent);
        if (!$parsed) {
            throw new Exception("Certificato CA non valido o corrotto.");
        }

        $isCA = isset($parsed['extensions']['basicConstraints']) && strpos($parsed['extensions']['basicConstraints'], 'CA:TRUE') !== false;
        if (!$isCA) {
            throw new Exception("Il file caricato non è un'Autorità di Certificazione (BasicConstraints CA:TRUE mancante).");
        }

        $cn = $parsed['subject']['CN'] ?? 'Unknown CA';
        $c  = $parsed['subject']['C'] ?? null;
        $st = $parsed['subject']['ST'] ?? null;
        $l  = $parsed['subject']['L'] ?? null;
        $o  = $parsed['subject']['O'] ?? null;
        $ou = $parsed['subject']['OU'] ?? null;
        
        $validFrom = date('Y-m-d H:i:s', $parsed['validFrom_time_t']);
        $validTo   = date('Y-m-d H:i:s', $parsed['validTo_time_t']);

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

        $ca = new self(
            pdo: $pdo,
            id: null,
            common_name: $cn,
            subject_country: $c,
            subject_state: $st,
            subject_locality: $l,
            subject_organization: $o,
            subject_org_unit: $ou,
            key_type: $keyType,
            key_bits: $keyBits,
            crl_distribution_points: null,
            ocsp_server: null,
            description: $description ?? 'CA Importata da file PEM',
            status: 'active',
            valid_from: $validFrom,
            valid_to: $validTo,
            cert_data: $certContent,
            created_at: null,
            key_data: $keyContent
        );

        if (!$ca->save()) {
            throw new Exception("Errore durante il salvataggio della CA nel database.");
        }

        return $ca;
    }

    /**
     * Recupera tutte le CA ordinate per data di creazione.
     * @return self[]
     */
    public static function findAll(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM cas ORDER BY created_at DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $cas = [];
        foreach ($rows as $data) {
            $cas[] = new self(
                $pdo,
                (int)$data['id'],
                $data['common_name'],
                $data['subject_country'] ?? null,
                $data['subject_state'] ?? null,
                $data['subject_locality'] ?? null,
                $data['subject_organization'] ?? null,
                $data['subject_org_unit'] ?? null,
                $data['key_type'] ?? 'rsa',
                (int)($data['key_bits'] ?? 4096),
                $data['crl_distribution_points'] ?? null,
                $data['ocsp_server'] ?? null,
                $data['description'] ?? null,
                $data['status'] ?? 'active',
                $data['valid_from'] ?? '',
                $data['valid_to'] ?? '',
                $data['cert_data'] ?? null,
                $data['created_at'] ?? '',
                $data['key_data'] ?? null
            );
        }
        return $cas;
    }

    public static function findById(PDO $pdo, int $id): ?self {
        $stmt = $pdo->prepare("SELECT * FROM cas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;

        return new self(
            $pdo,
            (int)$data['id'],
            $data['common_name'],
            $data['subject_country'] ?? null,
            $data['subject_state'] ?? null,
            $data['subject_locality'] ?? null,
            $data['subject_organization'] ?? null,
            $data['subject_org_unit'] ?? null,
            $data['key_type'] ?? 'rsa',
            (int)($data['key_bits'] ?? 4096),
            $data['crl_distribution_points'] ?? null,
            $data['ocsp_server'] ?? null,
            $data['description'] ?? null,
            $data['status'] ?? 'active',
            $data['valid_from'] ?? '',
            $data['valid_to'] ?? '',
            $data['cert_data'] ?? null,
            $data['created_at'] ?? '',
            $data['key_data'] ?? null
        );
    }

    public function save(): bool {
        if (!$this->pdo) {
            throw new Exception("Connessione PDO non disponibile.");
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO cas (
                common_name, subject_country, subject_state, subject_locality, 
                subject_organization, subject_org_unit, key_type, key_bits, 
                crl_distribution_points, ocsp_server, description, status, 
                valid_from, valid_to, cert_data, key_data
            ) VALUES (
                :common_name, :subject_country, :subject_state, :subject_locality, 
                :subject_organization, :subject_org_unit, :key_type, :key_bits, 
                :crl_distribution_points, :ocsp_server, :description, :status, 
                :valid_from, :valid_to, :cert_data, :key_data
            )
        ");

        $success = $stmt->execute([
            'common_name'             => $this->common_name,
            'subject_country'         => $this->subject_country,
            'subject_state'           => $this->subject_state,
            'subject_locality'        => $this->subject_locality,
            'subject_organization'    => $this->subject_organization,
            'subject_org_unit'        => $this->subject_org_unit,
            'key_type'                => $this->key_type,
            'key_bits'                => $this->key_bits,
            'crl_distribution_points' => $this->crl_distribution_points,
            'ocsp_server'             => $this->ocsp_server,
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

        $stmt = $this->pdo->prepare("UPDATE cas SET status = :status WHERE id = :id");
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
        $stmt = $this->pdo->prepare("DELETE FROM cas WHERE id = ?");
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
        return (int)$pdo->query("SELECT COUNT(*) FROM cas")->fetchColumn();
    }

    public function getId(): ?int { return $this->id; }
    public function getCommonName(): string { return $this->common_name; }
    public function getSubjectCountry(): ?string { return $this->subject_country; }
    public function getSubjectState(): ?string { return $this->subject_state; }
    public function getSubjectLocality(): ?string { return $this->subject_locality; }
    public function getSubjectOrganization(): ?string { return $this->subject_organization; }
    public function getSubjectOrgUnit(): ?string { return $this->subject_org_unit; }
    public function getKeyType(): string { return $this->key_type; }
    public function getKeyBits(): int { return $this->key_bits; }
    public function getCrlDistributionPoints(): ?string { return $this->crl_distribution_points; }
    public function getOcspServer(): ?string { return $this->ocsp_server; }
    public function getDescription(): ?string { return $this->description; }
    public function getStatus(): string { return $this->status; }
    public function getValidFrom(): string { return $this->valid_from; }
    public function getValidTo(): string { return $this->valid_to; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    
    // Wrapper sicuri: all'esterno sembrano getter normali, ma internamente verificano l'autorizzazione
    public function getCertData(): ?string { return $this->getSecureCertData(); }
    public function getKeyData(): ?string { return $this->getSecureKeyData(); }
}