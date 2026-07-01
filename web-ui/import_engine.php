<?php
require_once 'config.php';

class SSLImporter {

    // Importa una CA (Certificato + Chiave Privata facoltativa)
    public static function importCA($certContent, $keyContent = null) {
        global $pdo;

        // Legge e valida il certificato X509
        $parsed = openssl_x509_parse($certContent);
        if (!$parsed) {
            return ["success" => false, "message" => "Certificato CA non valido o corrotto."];
        }

        // Verifica se è davvero una CA
        $isCA = isset($parsed['extensions']['basicConstraints']) && strpos($parsed['extensions']['basicConstraints'], 'CA:TRUE') !== false;
        if (!$isCA) {
            return ["success" => false, "message" => "Il file caricato non è un'Autorità di Certificazione (BasicConstraints CA:TRUE mancante)."];
        }

        $cn = $parsed['subject']['CN'] ?? 'Unknown CA';
        $c  = $parsed['subject']['C'] ?? '';
        $st = $parsed['subject']['ST'] ?? '';
        $l  = $parsed['subject']['L'] ?? '';
        $o  = $parsed['subject']['O'] ?? '';
        $ou = $parsed['subject']['OU'] ?? '';
        
        $validFrom = date('Y-m-d H:i:s', $parsed['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $parsed['validTo_time_t']);

        // Inserimento nel database
        $stmt = $pdo->prepare("INSERT INTO cas (common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, cert_data, key_data, valid_from, valid_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$cn, $c, $st, $l, $o, $ou, $certContent, $keyContent, $validFrom, $validTo]);

        return $success ? ["success" => true, "message" => "CA '$cn' importata con successo!"] : ["success" => false, "message" => "Errore durante il salvataggio nel database."];
    }

    // Importa un Certificato Foglia collegandolo a una CA esistente
    public static function importCertificate($caId, $certContent, $keyContent = null, $sanString = '') {
        global $pdo;

        $parsed = openssl_x509_parse($certContent);
        if (!$parsed) {
            return ["success" => false, "message" => "Certificato non valido o corrotto."];
        }

        $cn = $parsed['subject']['CN'] ?? 'Unknown Cert';
        $c  = $parsed['subject']['C'] ?? '';
        $st = $parsed['subject']['ST'] ?? '';
        $l  = $parsed['subject']['L'] ?? '';
        $o  = $parsed['subject']['O'] ?? '';
        $ou = $parsed['subject']['OU'] ?? '';

        $validFrom = date('Y-m-d H:i:s', $parsed['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $parsed['validTo_time_t']);

        $stmt = $pdo->prepare("INSERT INTO certificates (ca_id, common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, san_dns, cert_data, key_data, valid_from, valid_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$caId, $cn, $c, $st, $l, $o, $ou, $sanString, $certContent, $keyContent, $validFrom, $validTo]);

        return $success ? ["success" => true, "message" => "Certificato '$cn' importato con successo!"] : ["success" => false, "message" => "Errore durante il salvataggio."];
    }
}