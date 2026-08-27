<?php

require_once 'Ca.php';

$pdo = new PDO("mysql:host=localhost;dbname=aegis_ca", "athena", "goat-snake-gorgon", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

echo "--- 1. Test Creazione e Salvataggio (save) ---\n";

// Creiamo un certificato fittizio (mock PEM) giusto per il test del salvataggio
$mockCertPem = "-----BEGIN CERTIFICATE-----\n" .
               "MIICljCCAX6gAwIBAgIUVDQ5...test...\n" .
               "-----END CERTIFICATE-----";

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
    created_at: null
);

$saved = $newCa->save();
if ($saved && $newCa->getId()) {
    echo "CA salvata con successo! ID assegnato: {$newCa->getId()}\n\n";
} else {
    echo "Errore durante il salvataggio della CA.\n";
    exit;
}

$caId = $newCa->getId();

echo "--- 2. Test Ricerca (findById) ---\n";
$fetchedCa = Ca::findById($pdo, $caId);

if ($fetchedCa) {
    echo "Trovata CA: {$fetchedCa->getCommonName()} [Status: {$fetchedCa->getStatus()}]\n";
    echo "Fingerprint SHA-256: {$fetchedCa->getFingerprint('sha256')}\n\n";
} else {
    echo "CA non trovata con ID {$caId}\n";
}

echo "--- 3. Test Modifica Stato (setStatus) ---\n";
if ($fetchedCa) {
    $updated = $fetchedCa->setStatus('revoked');
    if ($updated && $fetchedCa->isRevoked()) {
        echo "Stato aggiornato correttamente a: {$fetchedCa->getStatus()}\n";
        echo "isRevoked()? " . ($fetchedCa->isRevoked() ? 'Sì' : 'No') . "\n\n";
    } else {
        echo "Errore nell'aggiornamento dello stato.\n";
    }
}

echo "--- 4. Test Elenco Completo (findAll) ---\n";
$allCas = Ca::findAll($pdo);
echo "Totale CA nel database: " . count($allCas) . "\n";
foreach ($allCas as $ca) {
    echo " - [ID: {$ca->getId()}] {$ca->getCommonName()} ({$ca->getStatus()})\n";
}
echo "\n";

echo "--- 5. Test Eliminazione (delete) ---\n";
if ($fetchedCa) {
    $deleted = $fetchedCa->delete();
    if ($deleted) {
        echo "CA con ID {$caId} eliminata con successo dal database.\n";
    } else {
        echo "Errore durante l'eliminazione della CA.\n";
    }
}