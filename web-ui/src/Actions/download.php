<?php
// src/Actions/download.php
// Nota: Auth e la connessione globale $pdo sono già pronti grazie al PageController.

global $pdo;

if (!isset($_GET['type']) || !isset($_GET['id'])) {
    http_response_code(400);
    die("Parametri mancanti.");
}

$type = $_GET['type'];
$id = intval($_GET['id']);

if ($type === 'ca_cert' || $type === 'ca_key') {
    $stmt = $pdo->prepare("SELECT common_name, cert_data, key_data FROM cas WHERE id = ?");
    $stmt->execute([$id]);
    $res = $stmt->fetch();
    if (!$res) {
        http_response_code(404);
        die("CA non trovata.");
    }

    $filename = str_replace(' ', '_', $res['common_name']);
    if ($type === 'ca_cert') {
        header('Content-Type: application/x-x509-ca-cert');
        header('Content-Disposition: attachment; filename="' . $filename . '_ca.crt"');
        echo $res['cert_data'];
    } else {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '_ca.key"');
        echo $res['key_data'];
    }
} elseif ($type === 'cert_cert' || $type === 'cert_key') {
    $stmt = $pdo->prepare("SELECT common_name, cert_data, key_data FROM certificates WHERE id = ?");
    $stmt->execute([$id]);
    $res = $stmt->fetch();
    if (!$res) {
        http_response_code(404);
        die("Certificato non trovato.");
    }

    $filename = str_replace(' ', '_', $res['common_name']);
    if ($type === 'cert_cert') {
        header('Content-Type: application/x-x509-server-cert');
        header('Content-Disposition: attachment; filename="' . $filename . '.crt"');
        echo $res['cert_data'];
    } else {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '.key"');
        echo $res['key_data'];
    }
}
exit;