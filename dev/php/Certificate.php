<?php
// src/Classes/Certificate.php

class Certificate {
    private ?PDO $pdo;
    private ?int $id;
    private int $ca_id;
    private ?string $serial_number;
    private string $common_name;
    private ?string $issuer_dn;
    private string $subject_country;
    private string $subject_state;
    private string $subject_locality;
    private string $subject_organization;
    private string $subject_org_unit;
    private ?string $san_dns;
    private ?string $san_ip;
    private int $is_wildcard;
    private ?string $fingerprint_sha256;
    private ?string $cert_data;
    private ?string $key_data;
    private string $key_type;
    private int $key_bits;
    private ?string $signature_algorithm;
    private string $valid_from;
    private string $valid_to;
    private ?string $key_usage;
    private ?string $extended_key_usage;
    private ?string $basic_constraints;
    private ?string $crl_distribution_points;
    private ?string $ocsp_server;
    private ?string $description;
    private ?string $created_at;
    private string $status;
    private ?string $revoked_at;

    public function __construct(
        ?PDO $pdo,
        ?int $id,
        int $ca_id,
        ?string $serial_number,
        string $common_name,
        ?string $issuer_dn,
        string $subject_country,
        string $subject_state,
        string $subject_locality,
        string $subject_organization,
        string $subject_org_unit,
        ?string $san_dns,
        ?string $san_ip,
        int $is_wildcard,
        ?string $fingerprint_sha256,
        ?string $cert_data,
        ?string $key_data,
        string $key_type = 'rsa',
        int $key_bits = 2048,
        ?string $signature_algorithm = null,
        string $valid_from = '',
        string $valid_to = '',
        ?string $key_usage = null,
        ?string $extended_key_usage = null,
        ?string $basic_constraints = null,
        ?string $crl_distribution_points = null,
        ?string $ocsp_server = null,
        ?string $description = null,
        ?string $created_at = null,
        string $status = 'active',
        ?string $revoked_at = null
    ) {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->ca_id = $ca_id;
        $this->serial_number = $serial_number;
        $this->common_name = $common_name;
        $this->issuer_dn = $issuer_dn;
        $this->subject_country = $subject_country;
        $this->subject_state = $subject_state;
        $this->subject_locality = $subject_locality;
        $this->subject_organization = $subject_organization;
        $this->subject_org_unit = $subject_org_unit;
        $this->san_dns = $san_dns;
        $this->san_ip = $san_ip;
        $this->is_wildcard = $is_wildcard;
        $this->fingerprint_sha256 = $fingerprint_sha256;
        $this->cert_data = $cert_data;
        $this->key_data = $key_data;
        $this->key_type = $key_type;
        $this->key_bits = $key_bits;
        $this->signature_algorithm = $signature_algorithm;
        $this->valid_from = $valid_from;
        $this->valid_to = $valid_to;
        $this->key_usage = $key_usage;
        $this->extended_key_usage = $extended_key_usage;
        $this->basic_constraints = $basic_constraints;
        $this->crl_distribution_points = $crl_distribution_points;
        $this->ocsp_server = $ocsp_server;
        $this->description = $description;
        $this->created_at = $created_at;
        $this->status = $status;
        $this->revoked_at = $revoked_at;
    }

    public static function importFromPem(
        PDO $pdo, 
        int $caId, 
        string $certContent, 
        ?string $keyContent = null, 
        ?string $description = null
    ): self {
        $parsed = @openssl_x509_parse($certContent);
        if (!$parsed) {
            throw new Exception("Certificato non valido o corrotto.");
        }

        $isCA = isset($parsed['extensions']['basicConstraints']) && strpos($parsed['extensions']['basicConstraints'], 'CA:TRUE') !== false;
        if ($isCA) {
            throw new Exception("Il file caricato è un'Autorità di Certificazione (CA). Usa la sezione dedicata all'importazione delle CA.");
        }

        // Subject DN
        $cn = $parsed['subject']['CN'] ?? 'Unknown Cert';
        $c  = $parsed['subject']['C'] ?? '';
        $st = $parsed['subject']['ST'] ?? '';
        $l  = $parsed['subject']['L'] ?? '';
        $o  = $parsed['subject']['O'] ?? '';
        $ou = $parsed['subject']['OU'] ?? '';

        // Issuer DN e Serial
        $issuerDn = $parsed['issuer']['CN'] ?? $parsed['name'] ?? null;
        $serialNumber = isset($parsed['serialNumberHex']) ? $parsed['serialNumberHex'] : ($parsed['serialNumber'] ?? null);

        // Parsing SAN (Separazione DNS ed IP)
        $sanDns = null;
        $sanIp = null;
        if (!empty($parsed['extensions']['subjectAltName'])) {
            $parts = explode(',', $parsed['extensions']['subjectAltName']);
            $dnsArr = [];
            $ipArr = [];
            foreach ($parts as $part) {
                $part = trim($part);
                if (str_starts_with($part, 'DNS:')) {
                    $dnsArr[] = str_replace('DNS:', '', $part);
                } elseif (str_starts_with($part, 'IP Address:')) {
                    $ipArr[] = str_replace('IP Address:', '', $part);
                }
            }
            if (!empty($dnsArr)) $sanDns = implode(', ', $dnsArr);
            if (!empty($ipArr))  $sanIp  = implode(', ', $ipArr);
        }

        // Wildcard Flag
        $isWildcard = str_starts_with($cn, '*.') ? 1 : 0;

        // Estensioni e Algoritmi
        $fingerprint = openssl_x509_fingerprint($certContent, 'sha256');
        $signatureAlgorithm = $parsed['signatureTypeLN'] ?? $parsed['signatureTypeSN'] ?? null;
        $keyUsage = $parsed['extensions']['keyUsage'] ?? null;
        $extKeyUsage = $parsed['extensions']['extendedKeyUsage'] ?? null;
        $basicConstraints = $parsed['extensions']['basicConstraints'] ?? null;
        $crlPoints = $parsed['extensions']['crlDistributionPoints'] ?? null;
        $ocspServer = $parsed['extensions']['authorityInfoAccess'] ?? null;

        // Validità
        $validFromTimestamp = $parsed['validFrom_time_t'] ?? null;
        $validToTimestamp   = $parsed['validTo_time_t']   ?? null;
        $validFrom = $validFromTimestamp ? date('Y-m-d H:i:s', $validFromTimestamp) : date('Y-m-d H:i:s');
        $validTo   = $validToTimestamp   ? date('Y-m-d H:i:s', $validToTimestamp)   : date('Y-m-d H:i:s');

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

        if ($keyContent) {
            $privKey = @openssl_pkey_get_private($keyContent);
            if (!$privKey || !@openssl_x509_check_private_key($certContent, $keyContent)) {
                throw new Exception("La chiave privata fornita non corrisponde a questo certificato.");
            }
        }

        $stmtCheck = $pdo->prepare("SELECT 1 FROM cas WHERE id = ?");
        $stmtCheck->execute([$caId]);
        if (!$stmtCheck->fetchColumn()) {
            throw new Exception("La CA specificata (ID: {$caId}) non esiste nel database.");
        }

        $cert = new self(
            pdo: $pdo,
            id: null,
            ca_id: $caId,
            serial_number: $serialNumber,
            common_name: $cn,
            issuer_dn: $issuerDn,
            subject_country: $c,
            subject_state: $st,
            subject_locality: $l,
            subject_organization: $o,
            subject_org_unit: $ou,
            san_dns: $sanDns,
            san_ip: $sanIp,
            is_wildcard: $isWildcard,
            fingerprint_sha256: $fingerprint ? strtolower($fingerprint) : null,
            cert_data: $certContent,
            key_data: $keyContent,
            key_type: $keyType,
            key_bits: $keyBits,
            signature_algorithm: $signatureAlgorithm,
            valid_from: $validFrom,
            valid_to: $validTo,
            key_usage: $keyUsage,
            extended_key_usage: $extKeyUsage,
            basic_constraints: $basicConstraints,
            crl_distribution_points: $crlPoints,
            ocsp_server: $ocspServer,
            description: $description ?? 'Certificato importato da file PEM',
            created_at: null,
            status: 'active',
            revoked_at: null
        );

        if (!$cert->save()) {
            throw new Exception("Errore durante il salvataggio del certificato nel database.");
        }

        return $cert;
    }

    private static function mapRowToInstance(PDO $pdo, array $data): self {
        return new self(
            $pdo,
            (int)$data['id'],
            (int)$data['ca_id'],
            $data['serial_number'] ?? null,
            $data['common_name'],
            $data['issuer_dn'] ?? null,
            $data['subject_country'] ?? '',
            $data['subject_state'] ?? '',
            $data['subject_locality'] ?? '',
            $data['subject_organization'] ?? '',
            $data['subject_org_unit'] ?? '',
            $data['san_dns'] ?? null,
            $data['san_ip'] ?? null,
            (int)($data['is_wildcard'] ?? 0),
            $data['fingerprint_sha256'] ?? null,
            $data['cert_data'] ?? null,
            $data['key_data'] ?? null,
            $data['key_type'] ?? 'rsa',
            (int)($data['key_bits'] ?? 2048),
            $data['signature_algorithm'] ?? null,
            $data['valid_from'] ?? '',
            $data['valid_to'] ?? '',
            $data['key_usage'] ?? null,
            $data['extended_key_usage'] ?? null,
            $data['basic_constraints'] ?? null,
            $data['crl_distribution_points'] ?? null,
            $data['ocsp_server'] ?? null,
            $data['description'] ?? null,
            $data['created_at'] ?? null,
            $data['status'] ?? 'active',
            $data['revoked_at'] ?? null
        );
    }

    public static function findAll(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM certificates ORDER BY created_at DESC");
        return array_map(fn($data) => self::mapRowToInstance($pdo, $data), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function findById(PDO $pdo, int $id): ?self {
        $stmt = $pdo->prepare("SELECT * FROM certificates WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? self::mapRowToInstance($pdo, $data) : null;
    }

    public static function findByCaId(PDO $pdo, int $caId): array {
        $stmt = $pdo->prepare("SELECT * FROM certificates WHERE ca_id = :ca_id ORDER BY created_at DESC");
        $stmt->execute(['ca_id' => $caId]);
        return array_map(fn($data) => self::mapRowToInstance($pdo, $data), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function save(): bool {
        if (!$this->pdo) {
            throw new Exception("Connessione PDO non disponibile.");
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO certificates (
                ca_id, serial_number, common_name, issuer_dn, subject_country, subject_state, 
                subject_locality, subject_organization, subject_org_unit, san_dns, san_ip, 
                is_wildcard, fingerprint_sha256, cert_data, key_data, key_type, key_bits, 
                signature_algorithm, valid_from, valid_to, key_usage, extended_key_usage, 
                basic_constraints, crl_distribution_points, ocsp_server, description, status, revoked_at
            ) VALUES (
                :ca_id, :serial_number, :common_name, :issuer_dn, :subject_country, :subject_state, 
                :subject_locality, :subject_organization, :subject_org_unit, :san_dns, :san_ip, 
                :is_wildcard, :fingerprint_sha256, :cert_data, :key_data, :key_type, :key_bits, 
                :signature_algorithm, :valid_from, :valid_to, :key_usage, :extended_key_usage, 
                :basic_constraints, :crl_distribution_points, :ocsp_server, :description, :status, :revoked_at
            )
        ");

        $success = $stmt->execute([
            'ca_id'                   => $this->ca_id,
            'serial_number'           => $this->serial_number,
            'common_name'             => $this->common_name,
            'issuer_dn'               => $this->issuer_dn,
            'subject_country'         => $this->subject_country,
            'subject_state'           => $this->subject_state,
            'subject_locality'        => $this->subject_locality,
            'subject_organization'    => $this->subject_organization,
            'subject_org_unit'        => $this->subject_org_unit,
            'san_dns'                 => $this->san_dns,
            'san_ip'                  => $this->san_ip,
            'is_wildcard'             => $this->is_wildcard,
            'fingerprint_sha256'      => $this->fingerprint_sha256,
            'cert_data'               => $this->cert_data,
            'key_data'                => $this->key_data,
            'key_type'                => $this->key_type,
            'key_bits'                => $this->key_bits,
            'signature_algorithm'     => $this->signature_algorithm,
            'valid_from'              => $this->valid_from,
            'valid_to'                => $this->valid_to,
            'key_usage'               => $this->key_usage,
            'extended_key_usage'      => $this->extended_key_usage,
            'basic_constraints'       => $this->basic_constraints,
            'crl_distribution_points' => $this->crl_distribution_points,
            'ocsp_server'             => $this->ocsp_server,
            'description'             => $this->description,
            'status'                  => $this->status,
            'revoked_at'              => $this->revoked_at
        ]);

        if ($success) {
            $this->id = (int)$this->pdo->lastInsertId();
        }

        return $success;
    }

    public function setStatus(string $status): bool {
        if (!$this->pdo || !$this->id) return false;

        $revokedAt = ($status === 'revoked') ? date('Y-m-d H:i:s') : null;
        $stmt = $this->pdo->prepare("UPDATE certificates SET status = :status, revoked_at = :revoked_at WHERE id = :id");
        $success = $stmt->execute(['status' => $status, 'revoked_at' => $revokedAt, 'id' => $this->id]);

        if ($success) {
            $this->status = $status;
            $this->revoked_at = $revokedAt;
        }

        return $success;
    }

    public function delete(): bool {
        if (!$this->pdo || !$this->id) return false;
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
        if ($algo === 'sha256' && !empty($this->fingerprint_sha256)) {
            return strtoupper(implode(':', str_split($this->fingerprint_sha256, 2)));
        }

        $pemCert = $this->cert_data ?? '';
        if (empty(trim($pemCert))) return '-';
        
        $fp = @openssl_x509_fingerprint($pemCert, strtolower($algo), false);
        return ($fp !== false && !empty($fp)) ? strtoupper(implode(':', str_split($fp, 2))) : '-';
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

    public function isWildcard(): bool {
        return $this->is_wildcard === 1;
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

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getCaId(): int { return $this->ca_id; }
    public function getSerialNumber(): ?string { return $this->serial_number; }
    public function getCommonName(): string { return $this->common_name; }
    public function getIssuerDn(): ?string { return $this->issuer_dn; }
    public function getSubjectCountry(): string { return $this->subject_country; }
    public function getSubjectState(): string { return $this->subject_state; }
    public function getSubjectLocality(): string { return $this->subject_locality; }
    public function getSubjectOrganization(): string { return $this->subject_organization; }
    public function getSubjectOrgUnit(): string { return $this->subject_org_unit; }
    public function getSanDns(): ?string { return $this->san_dns; }
    public function getSanIp(): ?string { return $this->san_ip; }
    public function getIsWildcard(): int { return $this->is_wildcard; }
    public function getFingerprintSha256(): ?string { return $this->fingerprint_sha256; }
    public function getCertData(): ?string { return $this->getSecureCertData(); }
    public function getKeyData(): ?string { return $this->getSecureKeyData(); }
    public function getKeyType(): string { return $this->key_type; }
    public function getKeyBits(): int { return $this->key_bits; }
    public function getSignatureAlgorithm(): ?string { return $this->signature_algorithm; }
    public function getValidFrom(): string { return $this->valid_from; }
    public function getValidTo(): string { return $this->valid_to; }
    public function getKeyUsage(): ?string { return $this->key_usage; }
    public function getExtendedKeyUsage(): ?string { return $this->extended_key_usage; }
    public function getBasicConstraints(): ?string { return $this->basic_constraints; }
    public function getCrlDistributionPoints(): ?string { return $this->crl_distribution_points; }
    public function getOcspServer(): ?string { return $this->ocsp_server; }
    public function getDescription(): ?string { return $this->description; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function getStatus(): string { return $this->status; }
    public function getRevokedAt(): ?string { return $this->revoked_at; }
}