<?php
// tests/test_ca.php

require_once 'config.php';
require_once '../User.php';
require_once '../Auth.php';
require_once '../Ca.php';

// Avviamo la sessione e simuliamo un login per superare i controlli di sicurezza della classe Ca
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Creiamo un utente fittizio per simulare il login e testare i metodi protetti
$testUser = User::signup($pdo, "catest_" . rand(1000, 9999), "Password123!", "it");
Auth::login($pdo, $testUser->getUsername(), "Password123!");

echo "--- 1. Test Creazione e Salvataggio (save) ---\n";

$mockCertPem = "-----BEGIN CERTIFICATE-----\n" .
               "MIICljCCAX6gAwIBAgIUVDQ5...test...\n" .
               "-----END CERTIFICATE-----";

$mockKeyData = "-----BEGIN PRIVATE KEY-----\n" .
               "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC3...\n" .
               "-----END PRIVATE KEY-----";

$newCa = new Ca(
    pdo: $pdo,
    id: null,
    common_name: "Test Root CA " . rand(1000, 9999),
    subject_country: "IT",
    subject_state: "Lazio",
    subject_locality: "Rome",
    subject_organization: "Aegis Test Corp",
    subject_org_unit: "Security Dept",
    key_type: "rsa",
    key_bits: 4096,
    crl_distribution_points: "http://crl.example.com/crl",
    ocsp_server: "http://ocsp.example.com",
    description: "CA creata tramite script di test",
    status: "active",
    valid_from: date('Y-m-d H:i:s'),
    valid_to: date('Y-m-d H:i:s', strtotime('+10 years')),
    cert_data: $mockCertPem,
    created_at: null,
    key_data: $mockKeyData
);

$saved = $newCa->save();
if ($saved && $newCa->getId()) {
    echo $newCa->getCommonName() . " salvata con successo! ID assegnato: {$newCa->getId()}\n\n";
} else {
    echo "Errore durante il salvataggio della CA.\n";
    $testUser->delete();
    exit;
}

$caId = $newCa->getId();

echo "--- 2. Test Ricerca (findById) e Getter protetti ---\n";
$fetchedCa = Ca::findById($pdo, $caId);

if ($fetchedCa) {
    echo "Trovata CA: {$fetchedCa->getCommonName()} [Status: {$fetchedCa->getStatus()}]\n";
    echo "Fingerprint SHA-256: {$fetchedCa->getFingerprint('sha256')}\n";
    
    // Testiamo i getter protetti da Auth::isLoggedIn()
    try {
        $certCheck = !empty($fetchedCa->getCertData()) ? "Presente (Protetto OK)" : "Vuoto";
        $keyCheck = !empty($fetchedCa->getKeyData()) ? "Presente (Protetto OK)" : "Vuoto";
        echo " - Cert Data: {$certCheck}\n";
        echo " - Key Data: {$keyCheck}\n\n";
    } catch (Exception $e) {
        echo "Errore nei getter protetti: " . $e->getMessage() . "\n\n";
    }
} else {
    echo "CA non trovata con ID {$caId}\n\n";
}

echo "--- 3. Test Modifica Stato (setStatus) ---\n";
if ($fetchedCa) {
    $updated = $fetchedCa->setStatus('revoked');
    if ($updated && $fetchedCa->isRevoked()) {
        echo "Stato aggiornato correttamente a: {$fetchedCa->getStatus()}\n";
        echo "isRevoked()? " . ($fetchedCa->isRevoked() ? 'Sì' : 'No') . "\n\n";
    } else {
        echo "Errore nell'aggiornamento dello stato.\n\n";
    }
}

echo "--- 4. Test Elenco Completo (findAll) ---\n";
$allCas = Ca::findAll($pdo);
echo "Totale CA nel database: " . count($allCas) . "\n";
foreach ($allCas as $ca) {
    echo " - [ID: {$ca->getId()}] {$ca->getCommonName()} ({$ca->getStatus()})\n";
}
echo "\n";

echo "--- 5. Test Eliminazione (delete e pulizia memoria) ---\n";
if ($fetchedCa) {
    $deleted = $fetchedCa->delete();
    if ($deleted) {
        echo "CA con ID {$caId} eliminata dal DB.\n";
        // Verifichiamo che la memoria dell'oggetto sia stata pulita come implementato nel metodo delete()
        echo "Stato post-delete dell'oggetto in RAM:\n";
        echo " - ID (atteso 0): {$fetchedCa->getId()}\n";
        echo " - Common Name (atteso vuoto): '{$fetchedCa->getCommonName()}'\n\n";
    } else {
        echo "Errore durante l'eliminazione della CA.\n\n";
    }
}

// Pulizia finale dell'utente di supporto del test
Auth::logout();
$testUser->delete();
if ($testUser->getUsername() == "None") {
    echo "Variabile utente svuotata.\n";
} else {
    echo "Errore nello svuotamento della variabile.\n";
}
echo "Test completati e database pulito con successo!\n";