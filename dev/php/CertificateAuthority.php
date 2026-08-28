<?php
// src/Classes/CertificateAuthority.php

class CertificateAuthority {
    private ?PDO $pdo;
    private ?int $id;
    private ?string $serial_number;
    private ?string $subject_key_identifier;
    private ?string $fingerprint_sha256;
    private string $name;
    private string $subject_country;
    private string $subject_state;
    private string $subject_locality;
    private string $subject_organization;
    private string $subject_org_unit;
    private ?string $cert_data;
    private ?string $key_data;
    private string $key_type;
    private int $key_bits;
    private ?string $signature_algorithm;
    private ?string $ca_password;
    private int $is_intermediate;
    private ?int $path_len_constraint;
    private int $next_serial;
    private int $crl_number;
    private ?string $last_crl_update;
    private ?string $next_crl_update;
    private ?string $valid_from;
    private ?string $valid_to;
    private ?string $crl_distribution_points;
    private ?string $ocsp_server;
    private ?string $description;
    private ?string $created_at;
    private string $status;
    private ?string $revoked_at;

    public function __construct(
        ?PDO $pdo,
        ?int $id,
        ?string $serial_number,
        ?string $subject_key_identifier,
        ?string $fingerprint_sha256,
        string $name,
        string $subject_country,
        string $subject_state,
        string $subject_locality,
        string $subject_organization,
        string $subject_org_unit,
        ?string $cert_data,
        ?string $key_data,
        string $key_type = 'rsa',
        int $key_bits = 2048,
        ?string $signature_algorithm = null,
        ?string $ca_password = null,
        int $is_intermediate = 0,
        ?int $path_len_constraint = null,
        int $next_serial = 1,
        int $crl_number = 1,
        ?string $last_crl_update = null,
        ?string $next_crl_update = null,
        ?string $valid_from = null,
        ?string $valid_to = null,
        ?string $crl_distribution_points = null,
        ?string $ocsp_server = null,
        ?string $description = null,
        ?string $created_at = null,
        string $status = 'active',
        ?string $revoked_at = null
    ) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->serial_number = $serial_number;
        $this->subject_key_identifier = $subject_key_identifier;
        $this->fingerprint_sha256 = $fingerprint_sha256;
        $this->name = $name;
        $this->subject_country = $subject_country;
        $this->subject_state = $subject_state;
        $this->subject_locality = $subject_locality;
        $this->subject_organization = $subject_organization;
        $this->subject_org_unit = $subject_org_unit;
        $this->cert_data = $cert_data;
        $this->key_data = $key_data;
        $this->key_type = $key_type;
        $this->key_bits = $key_bits;
        $this->signature_algorithm = $signature_algorithm;
        $this->ca_password = $ca_password;
        $this->is_intermediate = $is_intermediate;
        $this->path_len_constraint = $path_len_constraint;
        $this->next_serial = $next_serial;
        $this->crl_number = $crl_number;
        $this->last_crl_update = $last_crl_update;
        $this->next_crl_update = $next_crl_update;
        $this->valid_from = $valid_from;
        $this->valid_to = $valid_to;
        $this->crl_distribution_points = $crl_distribution_points;
        $this->ocsp_server = $ocsp_server;
        $this->description = $description;
        $this->created_at = $created_at;
        $this->status = $status;
        $this->revoked_at = $revoked_at;
    }

    public static function importFromPem(
        PDO $pdo,
        string $certContent,
        ?string $keyContent = null,
        ?string $passphrase = null,
        ?string $description = null
    ): self {
        $parsed = @openssl_x509_parse($certContent);
        if (!$parsed) {
            throw new Exception("File di certificato CA non valido o corrotto.");
        }

        // Verifica flag CA
        $isCA = isset($parsed['extensions']['basicConstraints']) && str_contains($parsed['extensions']['basicConstraints'], 'CA:TRUE');
        if (!$isCA) {
            throw new Exception("Il certificato fornito non possiede il flag CA:TRUE nelle Basic Constraints.");
        }

        // Calcolo pathLen
        $pathLen = null;
        if (isset($parsed['extensions']['basicConstraints']) && preg_match('/pathlen:(\d+)/i', $parsed['extensions']['basicConstraints'], $matches)) {
            $pathLen = (int)$matches[1];
        }

        // Verifica Intermediate vs Root CA (Se Issuer == Subject -> Root)
        $isIntermediate = ($parsed['issuer'] !== $parsed['subject']) ? 1 : 0;

        // Subject DN
        $cn = $parsed['subject']['CN'] ?? $parsed['subject']['O'] ?? 'CA Sconosciuta';
        $c  = $parsed['subject']['C'] ?? '';
        $st = $parsed['subject']['ST'] ?? '';
        $l  = $parsed['subject']['L'] ?? '';
        $o  = $parsed['subject']['O'] ?? '';
        $ou = $parsed['subject']['OU'] ?? '';

        // Serial & SKI
        $serialNumber = $parsed['serialNumberHex'] ?? $parsed['serialNumber'] ?? null;
        $ski = $parsed['extensions']['subjectKeyIdentifier'] ?? null;
        if ($ski) {
            $ski = str_replace([':', ' '], '', $ski);
        }

        // Algoritmi e Fingerprint
        $fingerprint = openssl_x509_fingerprint($certContent, 'sha256');
        $signatureAlgorithm = $parsed['signatureTypeLN'] ?? $parsed['signatureTypeSN'] ?? null;

        // Date
        $validFrom = date('Y-m-d H:i:s', $parsed['validFrom_time_t'] ?? time());
        $validTo   = date('Y-m-d H:i:s', $parsed['validTo_time_t'] ?? time());

        // Estensioni CDP/AIA
        $crlPoints = $parsed['extensions']['crlDistributionPoints'] ?? null;
        $ocspServer = $parsed['extensions']['authorityInfoAccess'] ?? null;

        // Dettagli Chiave
        $keyBits = 2048;
        $keyType = 'rsa';
        $pubKey  = @openssl_pkey_get_public($certContent);
        if ($pubKey) {
            $keyDetails = openssl_pkey_get_details($pubKey);
            if (isset($keyDetails['bits'])) $keyBits = $keyDetails['bits'];
            if (isset($keyDetails['type'])) {
                $keyType = match ($keyDetails['type']) {
                    OPENSSL_KEYTYPE_RSA => 'rsa',
                    OPENSSL_KEYTYPE_EC  => 'ec',
                    OPENSSL_KEYTYPE_DSA => 'dsa',
                    default             => 'rsa',
                };
            }
        }

        // Validazione chiave privata opzionale
        if ($keyContent) {
            $privKey = @openssl_pkey_get_private($keyContent, $passphrase ?? '');
            if (!$privKey || !@openssl_x509_check_private_key($certContent, $privKey)) {
                throw new Exception("La chiave privata non corrisponde al certificato CA fornito o la passphrase è errata.");
            }
        }

        $ca = new self(
            pdo: $pdo,
            id: null,
            serial_number: $serialNumber,
            subject_key_identifier: $ski,
            fingerprint_sha256: $fingerprint ? strtolower($fingerprint) : null,
            name: $cn,
            subject_country: $c,
            subject_state: $st,
            subject_locality: $l,
            subject_organization: $o,
            subject_org_unit: $ou,
            cert_data: $certContent,
            key_data: $keyContent,
            key_type: $keyType,
            key_bits: $keyBits,
            signature_algorithm: $signatureAlgorithm,
            ca_password: $passphrase,
            is_intermediate: $isIntermediate,
            path_len_constraint: $pathLen,
            next_serial: 1,
            crl_number: 1,
            last_crl_update: null,
            next_crl_update: null,
            valid_from: $validFrom,
            valid_to: $validTo,
            crl_distribution_points: $crlPoints,
            ocsp_server: $ocspServer,
            description: $description ?? 'CA Importata da File PEM',
            created_at: null,
            status: 'active',
            revoked_at: null
        );

        if (!$ca->save()) {
            throw new Exception("Errore durante il salvataggio della CA nel database.");
        }

        return $ca;
    }

    private static function mapRowToInstance(PDO $pdo, array $data): self {
        return new self(
            $pdo,
            (int)$data['id'],
            $data['serial_number'] ?? null,
            $data['subject_key_identifier'] ?? null,
            $data['fingerprint_sha256'] ?? null,
            $data['name'],
            $data['subject_country'] ?? '',
            $data['subject_state'] ?? '',
            $data['subject_locality'] ?? '',
            $data['subject_organization'] ?? '',
            $data['subject_org_unit'] ?? '',
            $data['cert_data'] ?? null,
            $data['key_data'] ?? null,
            $data['key_type'] ?? 'rsa',
            (int)($data['key_bits'] ?? 2048),
            $data['signature_algorithm'] ?? null,
            $data['ca_password'] ?? null,
            (int)($data['is_intermediate'] ?? 0),
            isset($data['path_len_constraint']) ? (int)$data['path_len_constraint'] : null,
            (int)($data['next_serial'] ?? 1),
            (int)($data['crl_number'] ?? 1),
            $data['last_crl_update'] ?? null,
            $data['next_crl_update'] ?? null,
            $data['valid_from'] ?? null,
            $data['valid_to'] ?? null,
            $data['crl_distribution_points'] ?? null,
            $data['ocsp_server'] ?? null,
            $data['description'] ?? null,
            $data['created_at'] ?? null,
            $data['status'] ?? 'active',
            $data['revoked_at'] ?? null
        );
    }

    public static function findAll(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM cas ORDER BY created_at DESC");
        return array_map(fn($data) => self::mapRowToInstance($pdo, $data), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function findById(PDO $pdo, int $id): ?self {
        $stmt = $pdo->prepare("SELECT * FROM cas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? self::mapRowToInstance($pdo, $data) : null;
    }

    public function save(): bool {
        if (!$this->pdo) {
            throw new Exception("Connessione PDO non disponibile.");
        }

        if ($this->id === null) {
            $stmt = $this->pdo->prepare("
                INSERT INTO cas (
                    serial_number, subject_key_identifier, fingerprint_sha256, name, 
                    subject_country, subject_state, subject_locality, subject_organization, 
                    subject_org_unit, cert_data, key_data, key_type, key_bits, 
                    signature_algorithm, ca_password, is_intermediate, path_len_constraint, 
                    next_serial, crl_number, last_crl_update, next_crl_update, valid_from, 
                    valid_to, crl_distribution_points, ocsp_server, description, status, revoked_at
                ) VALUES (
                    :serial_number, :subject_key_identifier, :fingerprint_sha256, :name, 
                    :subject_country, :subject_state, :subject_locality, :subject_organization, 
                    :subject_org_unit, :cert_data, :key_data, :key_type, :key_bits, 
                    :signature_algorithm, :ca_password, :is_intermediate, :path_len_constraint, 
                    :next_serial, :crl_number, :last_crl_update, :next_crl_update, :valid_from, 
                    :valid_to, :crl_distribution_points, :ocsp_server, :description, :status, :revoked_at
                )
            ");
        } else {
            $stmt = $this->pdo->prepare("
                UPDATE cas SET 
                    serial_number = :serial_number,
                    subject_key_identifier = :subject_key_identifier,
                    fingerprint_sha256 = :fingerprint_sha256,
                    name = :name,
                    subject_country = :subject_country,
                    subject_state = :subject_state,
                    subject_locality = :subject_locality,
                    subject_organization = :subject_organization,
                    subject_org_unit = :subject_org_unit,
                    cert_data = :cert_data,
                    key_data = :key_data,
                    key_type = :key_type,
                    key_bits = :key_bits,
                    signature_algorithm = :signature_algorithm,
                    ca_password = :ca_password,
                    is_intermediate = :is_intermediate,
                    path_len_constraint = :path_len_constraint,
                    next_serial = :next_serial,
                    crl_number = :crl_number,
                    last_crl_update = :last_crl_update,
                    next_crl_update = :next_crl_update,
                    valid_from = :valid_from,
                    valid_to = :valid_to,
                    crl_distribution_points = :crl_distribution_points,
                    ocsp_server = :ocsp_server,
                    description = :description,
                    status = :status,
                    revoked_at = :revoked_at
                WHERE id = :id
            ");
        }

        $params = [
            'serial_number'           => $this->serial_number,
            'subject_key_identifier'  => $this->subject_key_identifier,
            'fingerprint_sha256'      => $this->fingerprint_sha256,
            'name'                    => $this->name,
            'subject_country'         => $this->subject_country,
            'subject_state'           => $this->subject_state,
            'subject_locality'        => $this->subject_locality,
            'subject_organization'    => $this->subject_organization,
            'subject_org_unit'        => $this->subject_org_unit,
            'cert_data'               => $this->cert_data,
            'key_data'                => $this->key_data,
            'key_type'                => $this->key_type,
            'key_bits'                => $this->key_bits,
            'signature_algorithm'     => $this->signature_algorithm,
            'ca_password'             => $this->ca_password,
            'is_intermediate'         => $this->is_intermediate,
            'path_len_constraint'     => $this->path_len_constraint,
            'next_serial'             => $this->next_serial,
            'crl_number'              => $this->crl_number,
            'last_crl_update'         => $this->last_crl_update,
            'next_crl_update'         => $this->next_crl_update,
            'valid_from'              => $this->valid_from,
            'valid_to'                => $this->valid_to,
            'crl_distribution_points' => $this->crl_distribution_points,
            'ocsp_server'             => $this->ocsp_server,
            'description'             => $this->description,
            'status'                  => $this->status,
            'revoked_at'              => $this->revoked_at
        ];

        if ($this->id !== null) {
            $params['id'] = $this->id;
        }

        $success = $stmt->execute($params);

        if ($success && $this->id === null) {
            $this->id = (int)$this->pdo->lastInsertId();
        }

        return $success;
    }

    public function generateNextSerialHex(): string {
        if (!$this->pdo || !$this->id) {
            throw new Exception("CA non salvata a DB.");
        }

        // Incremento atomico
        $stmt = $this->pdo->prepare("UPDATE cas SET next_serial = next_serial + 1 WHERE id = ?");
        $stmt->execute([$this->id]);

        $stmt = $this->pdo->prepare("SELECT next_serial FROM cas WHERE id = ?");
        $stmt->execute([$this->id]);
        $currentVal = (int)$stmt->fetchColumn();

        $this->next_serial = $currentVal;
        return strtoupper(dechex($currentVal));
    }

    public function incrementCrlNumber(int $validityDays = 7): int {
        if (!$this->pdo || !$this->id) {
            throw new Exception("CA non salvata a DB.");
        }

        $lastUpdate = date('Y-m-d H:i:s');
        $nextUpdate = date('Y-m-d H:i:s', strtotime("+{$validityDays} days"));

        $stmt = $this->pdo->prepare("
            UPDATE cas 
            SET crl_number = crl_number + 1,
                last_crl_update = :last_update,
                next_crl_update = :next_update
            WHERE id = :id
        ");
        $stmt->execute([
            'last_update' => $lastUpdate,
            'next_update' => $nextUpdate,
            'id'          => $this->id
        ]);

        $this->crl_number++;
        $this->last_crl_update = $lastUpdate;
        $this->next_crl_update = $nextUpdate;

        return $this->crl_number;
    }

    public function setStatus(string $status): bool {
        if (!$this->pdo || !$this->id) return false;

        $revokedAt = ($status === 'revoked') ? date('Y-m-d H:i:s') : null;
        $stmt = $this->pdo->prepare("UPDATE cas SET status = :status, revoked_at = :revoked_at WHERE id = :id");
        $success = $stmt->execute(['status' => $status, 'revoked_at' => $revokedAt, 'id' => $this->id]);

        if ($success) {
            $this->status = $status;
            $this->revoked_at = $revokedAt;
        }

        return $success;
    }

    public function getFingerprint(string $algo = 'sha256'): string {
        if ($algo === 'sha256' && !empty($this->fingerprint_sha256)) {
            return strtoupper(implode(':', str_split($this->fingerprint_sha256, 2)));
        }

        $pem = $this->cert_data ?? '';
        if (empty(trim($pem))) return '-';

        $fp = @openssl_x509_fingerprint($pem, strtolower($algo), false);
        return ($fp !== false && !empty($fp)) ? strtoupper(implode(':', str_split($fp, 2))) : '-';
    }

    private function getSecureCertData(): ?string {
        if (!class_exists('Auth') || !Auth::isLoggedIn()) {
            throw new Exception("Accesso negato: autenticazione richiesta.");
        }
        return $this->cert_data;
    }

    private function getSecureKeyData(): ?string {
        if (!class_exists('Auth') || !Auth::isLoggedIn()) {
            throw new Exception("Accesso negato: autenticazione richiesta.");
        }
        return $this->key_data;
    }

    public function isExpired(): bool {
        return $this->valid_to ? (strtotime($this->valid_to) < time()) : false;
    }

    public function isRevoked(): bool {
        return $this->status === 'revoked';
    }

    public function isIntermediate(): bool {
        return $this->is_intermediate === 1;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getSerialNumber(): ?string { return $this->serial_number; }
    public function getSubjectKeyIdentifier(): ?string { return $this->subject_key_identifier; }
    public function getFingerprintSha256(): ?string { return $this->fingerprint_sha256; }
    public function getName(): string { return $this->name; }
    public function getSubjectCountry(): string { return $this->subject_country; }
    public function getSubjectState(): string { return $this->subject_state; }
    public function getSubjectLocality(): string { return $this->subject_locality; }
    public function getSubjectOrganization(): string { return $this->subject_organization; }
    public function getSubjectOrgUnit(): string { return $this->subject_org_unit; }
    public function getCertData(): ?string { return $this->getSecureCertData(); }
    public function getKeyData(): ?string { return $this->getSecureKeyData(); }
    public function getKeyType(): string { return $this->key_type; }
    public function getKeyBits(): int { return $this->key_bits; }
    public function getSignatureAlgorithm(): ?string { return $this->signature_algorithm; }
    public function getCaPassword(): ?string { return $this->ca_password; }
    public function getIsIntermediate(): int { return $this->is_intermediate; }
    public function getPathLenConstraint(): ?int { return $this->path_len_constraint; }
    public function getNextSerial(): int { return $this->next_serial; }
    public function getCrlNumber(): int { return $this->crl_number; }
    public function getLastCrlUpdate(): ?string { return $this->last_crl_update; }
    public function getNextCrlUpdate(): ?string { return $this->next_crl_update; }
    public function getValidFrom(): ?string { return $this->valid_from; }
    public function getValidTo(): ?string { return $this->valid_to; }
    public function getCrlDistributionPoints(): ?string { return $this->crl_distribution_points; }
    public function getOcspServer(): ?string { return $this->ocsp_server; }
    public function getDescription(): ?string { return $this->description; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function getStatus(): string { return $this->status; }
    public function getRevokedAt(): ?string { return $this->revoked_at; }
}