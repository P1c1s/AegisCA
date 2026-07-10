<?php
// src/Classes/ImportEngine.php

class ImportEngine {

    /**
     * Importa una CA (Certificato + Chiave Privata facoltativa)
     */
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

        // Estrazione dei dati del Subject
        $cn = $parsed['subject']['CN'] ?? 'Unknown CA';
        $c  = $parsed['subject']['C'] ?? '';
        $st = $parsed['subject']['ST'] ?? '';
        $l  = $parsed['subject']['L'] ?? '';
        $o  = $parsed['subject']['O'] ?? '';
        $ou = $parsed['subject']['OU'] ?? '';
        
        $validFrom = date('Y-m-d H:i:s', $parsed['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $parsed['validTo_time_t']);

        // Estrazione dinamica dei key_bits per la CA
        $keyBits = 2048; // Fallback predefinito
        $pubKey = @openssl_pkey_get_public($certContent);
        if ($pubKey) {
            $keyDetails = openssl_pkey_get_details($pubKey);
            if (isset($keyDetails['bits'])) {
                $keyBits = $keyDetails['bits']; // Rileva i bit reali (es. 2048, 4096)
            }
        }

        // Inserimento nel database
        try {
            $stmt = $pdo->prepare("
                INSERT INTO cas (
                    common_name, subject_country, subject_state, subject_locality, 
                    subject_organization, subject_org_unit, cert_data, key_data, 
                    key_bits, valid_from, valid_to
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $success = $stmt->execute([
                $cn, $c, $st, $l, 
                $o, $ou, $certContent, $keyContent, 
                $keyBits, $validFrom, $validTo
            ]);

            return $success ? ["success" => true, "message" => "CA '$cn' importata con successo ($keyBits bit)!"] : ["success" => false, "message" => "Errore durante il salvataggio nel database."];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Errore di database CA: " . $e->getMessage()];
        }
    }

    /**
     * Importa un Certificato Foglia collegandolo a una CA esistente
     */
    public static function importCertificate($caId, $certContent, $keyContent = null, $sanString = '') {
        global $pdo;

        // Legge e valida il certificato X509
        $parsed = openssl_x509_parse($certContent);
        if (!$parsed) {
            return ["success" => false, "message" => "Certificato non valido o corrotto."];
        }

        // Estrazione dei dati del Subject
        $cn = $parsed['subject']['CN'] ?? 'Unknown Cert';
        $c  = $parsed['subject']['C'] ?? '';
        $st = $parsed['subject']['ST'] ?? '';
        $l  = $parsed['subject']['L'] ?? '';
        $o  = $parsed['subject']['O'] ?? '';
        $ou = $parsed['subject']['OU'] ?? '';

        $validFrom = date('Y-m-d H:i:s', $parsed['parsed']['validFrom_time_t'] ?? $parsed['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $parsed['parsed']['validTo_time_t'] ?? $parsed['validTo_time_t']);

        // --- RISOLTO: Estrazione dinamica dei key_bits anche per il certificato foglia ---
        $keyBits = 2048; // Fallback predefinito
        $pubKey = @openssl_pkey_get_public($certContent);
        if ($pubKey) {
            $keyDetails = openssl_pkey_get_details($pubKey);
            if (isset($keyDetails['bits'])) {
                $keyBits = $keyDetails['bits']; // Rileva i bit reali (es. 2048, 4096)
            }
        }

        // Inserimento nel database (Aggiunto il segnaposto per key_bits)
        try {
            $stmt = $pdo->prepare("
                INSERT INTO certificates (
                    ca_id, common_name, subject_country, subject_state, subject_locality, 
                    subject_organization, subject_org_unit, san_dns, cert_data, key_data, 
                    key_bits, valid_from, valid_to
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $success = $stmt->execute([
                $caId, $cn, $c, $st, $l, 
                $o, $ou, $sanString, $certContent, $keyContent, 
                $keyBits, $validFrom, $validTo
            ]);

            return $success ? ["success" => true, "message" => "Certificato '$cn' importato con successo ($keyBits bit)!"] : ["success" => false, "message" => "Errore durante il salvataggio."];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Errore di database Certificato: " . $e->getMessage()];
        }
    }
}