<?php
// src/Classes/SslEngine.php

class SslEngine {
    
    private static function parseDN($c, $st, $l, $o, $ou, $cn) {
        return [
            "countryName" => strtoupper(substr(trim($c ?? ''), 0, 2)),
            "stateOrProvinceName" => trim($st ?? ''),
            "localityName" => trim($l ?? ''),
            "organizationName" => trim($o ?? ''),
            "organizationalUnitName" => trim($ou ?? ''),
            "commonName" => trim($cn ?? '')
        ];
    }

    /**
     * Crea una nuova Certificate Authority (Root CA)
     */
    public static function createCA($dnData, $days = 3650, $keyBits = 4096, $caPassword = null) {
        global $pdo;
        
        $keyBits = intval($keyBits);
        if (!in_array($keyBits, [2048, 3072, 4096])) {
            $keyBits = 4096; 
        }

        $dn = self::parseDN($dnData['c'], $dnData['st'], $dnData['l'], $dnData['o'], $dnData['ou'], $dnData['cn']);
        
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM cas WHERE common_name = ?");
        $checkStmt->execute([$dn['commonName']]);
        if ($checkStmt->fetchColumn() > 0) {
            throw new Exception("Errore: Una Certificate Authority con il Common Name '{$dn['commonName']}' esiste già.");
        }

        $privKey = openssl_pkey_new([
            "private_key_bits" => $keyBits,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);
        
        if ($privKey === false) {
            throw new Exception("Errore OpenSSL nella generazione della chiave privata della CA: " . openssl_error_string());
        }

        $pkeyOut = '';
        if (!empty($caPassword)) {
            openssl_pkey_export($privKey, $pkeyOut, $caPassword);
        } else {
            openssl_pkey_export($privKey, $pkeyOut);
        }

        $csr = openssl_csr_new($dn, $privKey, ['digest_alg' => 'sha256']);
        if ($csr === false) {
            throw new Exception("Errore OpenSSL nella creazione della CSR della CA: " . openssl_error_string());
        }

        $x509 = openssl_csr_sign($csr, null, $privKey, $days, ['digest_alg' => 'sha256', 'x509_extensions' => 'v3_ca']);
        if ($x509 === false) {
            throw new Exception("Errore OpenSSL nella firma del certificato della CA: " . openssl_error_string());
        }

        openssl_x509_export($x509, $certOut);

        $parsed = openssl_x509_parse($x509);
        $validFrom = date('Y-m-d H:i:s', $parsed['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $parsed['validTo_time_t']);

        $stmt = $pdo->prepare("INSERT INTO cas (common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, cert_data, key_data, key_bits, valid_from, valid_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$dn['commonName'], $dn['countryName'], $dn['stateOrProvinceName'], $dn['localityName'], $dn['organizationName'], $dn['organizationalUnitName'], $certOut, $pkeyOut, $keyBits, $validFrom, $validTo]);
    }

    /**
     * Rilascia e firma un certificato SSL foglia usando una CA del sistema
     */
    public static function createCertificate($caId, $dnData, $sanString, $days = 825, $keyBits = 2048, $caPassword = null) {
        global $pdo;
        
        $keyBits = intval($keyBits);
        if (!in_array($keyBits, [2048, 3072, 4096])) {
            $keyBits = 2048; 
        }
        
        $dn = self::parseDN($dnData['c'], $dnData['st'], $dnData['l'], $dnData['o'], $dnData['ou'], $dnData['cn']);

        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM certificates WHERE common_name = ?");
        $checkStmt->execute([$dn['commonName']]);
        if ($checkStmt->fetchColumn() > 0) {
            throw new Exception("Errore: Un certificato con il Common Name '{$dn['commonName']}' esiste già nel sistema.");
        }
        
        $stmt = $pdo->prepare("SELECT * FROM cas WHERE id = ?");
        $stmt->execute([$caId]);
        $ca = $stmt->fetch();
        if (!$ca) return false;

        $caCert = $ca['cert_data'];
        
        $isKeyEncrypted = (strpos($ca['key_data'], 'ENCRYPTED') !== false);
        if ($isKeyEncrypted && empty($caPassword)) {
            throw new Exception("Errore: La chiave privata di questa CA è protetta da password. Inserisci la password di sblocco per procedere.");
        }

        $caKey = openssl_pkey_get_private($ca['key_data'], $caPassword);
        if ($caKey === false) {
            $sslError = openssl_error_string();
            if (strpos($sslError, 'bad decrypt') !== false || strpos($sslError, 'wrong passphrase') !== false) {
                throw new Exception("Errore: La password inserita per sbloccare la CA è errata.");
            }
            throw new Exception("Impossibile caricare o sbloccare la chiave privata della CA. Errore OpenSSL: " . $sslError);
        }

        $privKey = openssl_pkey_new([
            "private_key_bits" => $keyBits,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);
        
        if ($privKey === false) {
            throw new Exception("Errore OpenSSL nella generazione della chiave del certificato: " . openssl_error_string());
        }
        
        openssl_pkey_export($privKey, $pkeyOut);

        $configArgs = ['digest_alg' => 'sha256'];
        $tmpConfPath = null;

        try {
            if (!empty($sanString)) {
                $sans = array_map('trim', explode(',', $sanString));
                $sansFormatted = [];
                foreach ($sans as $san) {
                    if (filter_var($san, FILTER_VALIDATE_IP)) {
                        $sansFormatted[] = "IP:$san";
                    } else {
                        $sansFormatted[] = "DNS:$san";
                    }
                }
                
                $tmpConfPath = tempnam(sys_get_temp_dir(), 'ssl_cfg_');
                $confContent = "[req]\n"
                             . "distinguished_name = req_distinguished_name\n"
                             . "req_extensions = v3_req\n\n"
                             . "[req_distinguished_name]\n"
                             . "countryName = Country Name (2 letter code)\n"
                             . "commonName = Common Name (e.g. server FQDN)\n\n"
                             . "[v3_req]\n"
                             . "basicConstraints = CA:FALSE\n"
                             . "keyUsage = digitalSignature, keyEncipherment\n"
                             . "subjectAltName = " . implode(',', $sansFormatted) . "\n";
                file_put_contents($tmpConfPath, $confContent);
                
                $configArgs['config'] = $tmpConfPath;
                $configArgs['req_extensions'] = 'v3_req';
                $configArgs['x509_extensions'] = 'v3_req';
            }

            $csr = openssl_csr_new($dn, $privKey, $configArgs);
            if ($csr === false) {
                throw new Exception("Errore nella creazione della CSR: " . openssl_error_string());
            }

            $x509 = openssl_csr_sign($csr, $caCert, $caKey, $days, $configArgs);
            if ($x509 === false) {
                throw new Exception("Errore nella firma del certificato: " . openssl_error_string());
            }

            openssl_x509_export($x509, $certOut);

            $parsed = openssl_x509_parse($x509);
            $validFrom = date('Y-m-d H:i:s', $parsed['validFrom_time_t']);
            $validTo = date('Y-m-d H:i:s', $parsed['validTo_time_t']);

            if (PHP_VERSION_ID < 80000 && is_resource($caKey)) {
                openssl_free_key($caKey);
            }

            $stmt = $pdo->prepare("INSERT INTO certificates (ca_id, common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, san_dns, cert_data, key_data, key_bits, valid_from, valid_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([$caId, $dn['commonName'], $dn['countryName'], $dn['stateOrProvinceName'], $dn['localityName'], $dn['organizationName'], $dn['organizationalUnitName'], $sanString, $certOut, $pkeyOut, $keyBits, $validFrom, $validTo]);

        } finally {
            // Garanzia assoluta di eliminazione del file di configurazione provvisorio
            if ($tmpConfPath && file_exists($tmpConfPath)) {
                unlink($tmpConfPath);
            }
        }
    }
}