<?php
require_once 'config.php';

class SSLEngine {
    
    private static function parseDN($c, $st, $l, $o, $ou, $cn) {
        return [
            "countryName" => strtoupper(substr(trim($c), 0, 2)),
            "stateOrProvinceName" => trim($st),
            "localityName" => trim($l),
            "organizationName" => trim($o),
            "organizationalUnitName" => trim($ou),
            "commonName" => trim($cn)
        ];
    }

    public static function createCA($dnData, $days = 3650) {
        global $pdo;
        $dn = self::parseDN($dnData['c'], $dnData['st'], $dnData['l'], $dnData['o'], $dnData['ou'], $dnData['cn']);
        
        // Generazione Chiave Privata
        $privKey = openssl_pkey_new([
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);
        openssl_pkey_export($privKey, $pkeyOut);

        // Creazione CSR ed Auto-firma (Root CA)
        $csr = openssl_csr_new($dn, $privKey, ['digest_alg' => 'sha256']);
        $x509 = openssl_csr_sign($csr, null, $privKey, $days, ['digest_alg' => 'sha256', 'x509_extensions' => 'v3_ca']);
        openssl_x509_export($x509, $certOut);

        // Parsing delle date di validità
        $parsed = openssl_x509_parse($x509);
        $validFrom = date('Y-m-d H:i:s', $parsed['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $parsed['validTo_time_t']);

        $stmt = $pdo->prepare("INSERT INTO cas (common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, cert_data, key_data, valid_from, valid_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$dn['commonName'], $dn['countryName'], $dn['stateOrProvinceName'], $dn['localityName'], $dn['organizationName'], $dn['organizationalUnitName'], $certOut, $pkeyOut, $validFrom, $validTo]);
    }

    public static function createCertificate($caId, $dnData, $sanString, $days = 825) {
        global $pdo;
        
        // Recupero i dati della CA firmataria
        $stmt = $pdo->prepare("SELECT * FROM cas WHERE id = ?");
        $stmt->execute([$caId]);
        $ca = $stmt->fetch();
        if (!$ca) return false;

        $caCert = $ca['cert_data'];
        $caKey = $ca['key_data'];

        $dn = self::parseDN($dnData['c'], $dnData['st'], $dnData['l'], $dnData['o'], $dnData['ou'], $dnData['cn']);

        // Generazione Chiave Privata del certificato foglia
        $privKey = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);
        openssl_pkey_export($privKey, $pkeyOut);

        // Configurazione delle estensioni SAN (Subject Alternative Names)
        $configArgs = ['digest_alg' => 'sha256'];
        if (!empty($sanString)) {
            $sans = array_map('trim', explode(',', $sanString));
            $sansFormatted = [];
            foreach ($sans as $i => $san) {
                if (filter_var($san, FILTER_VALIDATE_IP)) {
                    $sansFormatted[] = "IP." . ($i+1) . " = $san";
                } else {
                    $sansFormatted[] = "DNS." . ($i+1) . " = $san";
                }
            }
            // Creazione di un file di configurazione temporaneo in memoria per gestire le estensioni SAN
            $tmpConf = tmpfile();
            $tmpConfPath = stream_get_meta_data($tmpConf)['uri'];
            $confContent = "[req]\ndistinguished_name=req_distinguished_name\n[req_distinguished_name]\n[v3_req]\nsubjectAltName=" . implode(',', $sansFormatted);
            fwrite($tmpConf, $confContent);
            $configArgs['config'] = $tmpConfPath;
            $configArgs['x509_extensions'] = 'v3_req';
        }

        $csr = openssl_csr_new($dn, $privKey, $configArgs);
        $x509 = openssl_csr_sign($csr, $caCert, $caKey, $days, $configArgs);
        openssl_x509_export($x509, $certOut);

        $parsed = openssl_x509_parse($x509);
        $validFrom = date('Y-m-d H:i:s', $parsed['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $parsed['validTo_time_t']);

        $stmt = $pdo->prepare("INSERT INTO certificates (ca_id, common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, san_dns, cert_data, key_data, valid_from, valid_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$caId, $dn['commonName'], $dn['countryName'], $dn['stateOrProvinceName'], $dn['localityName'], $dn['organizationName'], $dn['organizationalUnitName'], $sanString, $certOut, $pkeyOut, $validFrom, $validTo]);
    }
}
