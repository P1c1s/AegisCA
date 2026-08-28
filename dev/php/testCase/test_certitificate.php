<?php
// tests/test_certificate.php

require_once 'config.php';
require_once '../User.php';
require_once '../Auth.php';
require_once '../Ca.php';
require_once '../Certificate.php';

// Avviamo la sessione e simuliamo il login per superare i controlli di sicurezza (Auth::isLoggedIn)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$testUser = User::signup($pdo, "certtest_" . rand(1000, 9999), "Password123!", "it");
Auth::login($pdo, $testUser->getUsername(), "Password123!");

echo "--- 0. Creazione CA di supporto per il test ---\n";
$mockCaPem = "-----BEGIN CERTIFICATE-----\n" .
               "MIICljCCAX6gAwIBAgIUVDQ5...ca test...\n" .
               "-----END CERTIFICATE-----";

$newCa = new Ca(
    pdo: $pdo,
    id: null,
    common_name: "Test Root CA per Certificati " . rand(1000, 9999),
    subject_country: "IT",
    subject_state: "Lazio",
    subject_locality: "Rome",
    subject_organization: "Aegis Test Corp",
    subject_org_unit: "Security Dept",
    key_type: "rsa",
    key_bits: 4096,
    crl_distribution_points: "http://crl.example.com/crl",
    ocsp_server: "http://ocsp.example.com",
    description: "CA creata automaticamente per testare il certificato",
    status: "active",
    valid_from: date('Y-m-d H:i:s'),
    valid_to: date('Y-m-d H:i:s', strtotime('+10 years')),
    cert_data: $mockCaPem,
    created_at: null,
    key_data: "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqh...mock...\n-----END PRIVATE KEY-----"
);

if ($newCa->save()) {
    echo "CA creata con successo! ID: {$newCa->getId()}\n\n";
} else {
    echo "Errore nella creazione della CA di supporto.\n";
    $testUser->delete();
    exit;
}

$caId = $newCa->getId();

echo "--- 1. Test Creazione e Salvataggio Certificato Foglia (save) ---\n";

$mockCertPem = "-----BEGIN CERTIFICATE-----\n" .
               "MIICljCCAX6gAwIBAgIUF123...leaf test...\n" .
               "-----END CERTIFICATE-----";

$mockKeyData = "-----BEGIN PRIVATE KEY-----\n" .
               "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC3...\n" .
               "-----END PRIVATE KEY-----";

$newCert = new Certificate(
    pdo: $pdo,
    id: null,
    ca_id: $caId,
    common_name: "test-server-" . rand(1000, 9999) . ".example.com",
    subject_country: "IT",
    subject_state: "Lazio",
    subject_locality: "Rome",
    subject_organization: "Aegis Test Corp",
    subject_org_unit: "IT Dept",
    key_type: "rsa",
    key_bits: 2048,
    description: "Certificato SSL foglia creato tramite script di test",
    status: "active",
    valid_from: date('Y-m-d H:i:s'),
    valid_to: date('Y-m-d H:i:s', strtotime('+1 year')),
    cert_data: $mockCertPem,
    created_at: null,
    key_data: $mockKeyData
);

$saved = $newCert->save();
if ($saved && $newCert->getId()) {
    echo "Certificato salvato con successo! ID assegnato: {$newCert->getId()}\n\n";
} else {
    echo "Errore durante il salvataggio del certificato.\n";
    $newCa->delete();
    $testUser->delete();
    exit;
}

$certId = $newCert->getId();

echo "--- 2. Test Ricerca (findById) e Getter Sicuri ---\n";
$fetchedCert = Certificate::findById($pdo, $certId);

if ($fetchedCert) {
    echo "Trovato Certificato: {$fetchedCert->getCommonName()} [Status: {$fetchedCert->getStatus()}]\n";
    echo "Emesso da CA ID: {$fetchedCert->getCaId()}\n";
    echo "Fingerprint SHA-256: {$fetchedCert->getFingerprint('sha256')}\n";
    
    // Testiamo i wrapper protetti da Auth
    try {
        $certDataCheck = !empty($fetchedCert->getCertData()) ? "Presente (Protetto OK)" : "Vuoto";
        $keyDataCheck  = !empty($fetchedCert->getKeyData()) ? "Presente (Protetto OK)" : "Vuoto";
        echo " - Cert Data: {$certDataCheck}\n";
        echo " - Key Data: {$keyDataCheck}\n\n";
    } catch (Exception $e) {
        echo "Errore nei dati protetti: " . $e->getMessage() . "\n\n";
    }
} else {
    echo "Certificato non trovato con ID {$certId}\n\n";
}

echo "--- 3. Test Ricerca per CA (findByCaId) ---\n";
$certsByCa = Certificate::findByCaId($pdo, $caId);
echo "Certificati emessi dalla CA ID {$caId}: " . count($certsByCa) . "\n";
foreach ($certsByCa as $c) {
    echo " - [ID: {$c->getId()}] {$c->getCommonName()}\n";
}
echo "\n";

echo "--- 4. Test Modifica Stato (setStatus) ---\n";
if ($fetchedCert) {
    $updated = $fetchedCert->setStatus('revoked');
    if ($updated && $fetchedCert->isRevoked()) {
        echo "Stato aggiornato correttamente a: {$fetchedCert->getStatus()}\n";
        echo "isRevoked()? " . ($fetchedCert->isRevoked() ? 'Sì' : 'No') . "\n\n";
    } else {
        echo "Errore nell'aggiornamento dello stato.\n\n";
    }
}

echo "--- 5. Test Elenco Completo (findAll) e Conteggio (countAll) ---\n";
$totalCount = Certificate::countAll($pdo);
$allCerts = Certificate::findAll($pdo);
echo "Totale certificati nel database (countAll): {$totalCount} | (count array): " . count($allCerts) . "\n";
foreach ($allCerts as $cert) {
    echo " - [ID: {$cert->getId()}] {$cert->getCommonName()} ({$cert->getStatus()})\n";
}
echo "\n";

echo "--- 6. Test Pulizia (Eliminazione Certificato e CA di test) ---\n";
if ($fetchedCert) {
    $deletedCert = $fetchedCert->delete();
    if ($deletedCert) {
        echo "Certificato eliminato dal DB con successo.\n";
        $printId = $fetchedCert->getId() ?? 'null (corretto)';
        echo "Stato post-delete dell'oggetto in RAM:\n";
        echo " - ID (atteso null): {$printId}\n";
        echo " - Common Name (atteso vuoto): '{$fetchedCert->getCommonName()}'\n\n";
    } else {
        echo "Errore durante l'eliminazione del certificato.\n\n";
    }
}

$deletedCa = $newCa->delete();
echo "CA di supporto eliminata: " . ($deletedCa ? "Sì" : "No") . "\n\n";

// Pulizia finale dell'utente di supporto e chiusura sessione
Auth::logout();
$testUser->delete();
if ($testUser->getUsername() === "") {
    echo "Variabile utente svuotata correttamente.\n";
} else {
    echo "Errore nello svuotamento della variabile utente.\n";
}
echo "Tutti i test sui certificati sono stati completati e il database è pulito!\n";