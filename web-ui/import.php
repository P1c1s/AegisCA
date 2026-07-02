<?php
require_once 'auth.php';
require_once 'ssl_engine.php';
Auth::check();

$msg = ''; $type = '';

// Classe interna per l'importazione (sfrutta openssl_x509_parse)
class SSLImporter {
    
    // Funzione helper interna per estrarre la lunghezza della chiave (bits) dal certificato
    private static function getKeyBitsFromCert($certContent) {
        $pubKey = openssl_pkey_get_public($certContent);
        if ($pubKey) {
            $details = openssl_pkey_get_details($pubKey);
            if (isset($details['bits'])) {
                return intval($details['bits']);
            }
        }
        return 2048; // Default di fallback se non riesce a leggerla
    }

    public static function importCA($certContent, $keyContent = null) {
        global $pdo;
        $parsed = openssl_x509_parse($certContent);
        if (!$parsed) {
            return ["success" => false, "message" => "Certificato CA non valido o corrotto."];
        }

        // Verifica se è una CA
        $isCA = isset($parsed['extensions']['basicConstraints']) && strpos($parsed['extensions']['basicConstraints'], 'CA:TRUE') !== false;
        if (!$isCA) {
            return ["success" => false, "message" => "Il file non è una Certificate Authority (BasicConstraints CA:TRUE mancante)."];
        }

        $cn = $parsed['subject']['CN'] ?? 'Unknown CA';
        $c  = $parsed['subject']['C'] ?? '';
        $st = $parsed['subject']['ST'] ?? '';
        $l  = $parsed['subject']['L'] ?? '';
        $o  = $parsed['subject']['O'] ?? '';
        $ou = $parsed['subject']['OU'] ?? '';
        
        $validFrom = date('Y-m-d H:i:s', $parsed['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $parsed['validTo_time_t']);

        // Estrazione dinamica della lunghezza della chiave
        $keyBits = self::getKeyBitsFromCert($certContent);

        // Aggiunto key_bits nella colonna e nel binding dei parametri
        $stmt = $pdo->prepare("INSERT INTO cas (common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, cert_data, key_data, key_bits, valid_from, valid_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$cn, $c, $st, $l, $o, $ou, $certContent, $keyContent, $keyBits, $validFrom, $validTo]);

        return $success ? ["success" => true, "message" => "CA '$cn' importata con successo!"] : ["success" => false, "message" => "Errore nel salvataggio del database."];
    }

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

        // Estrazione dinamica della lunghezza della chiave
        $keyBits = self::getKeyBitsFromCert($certContent);

        // Aggiunto key_bits nella colonna e nel binding dei parametri
        $stmt = $pdo->prepare("INSERT INTO certificates (ca_id, common_name, subject_country, subject_state, subject_locality, subject_organization, subject_org_unit, san_dns, cert_data, key_data, key_bits, valid_from, valid_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$caId, $cn, $c, $st, $l, $o, $ou, $sanString, $certContent, $keyContent, $keyBits, $validFrom, $validTo]);

        return $success ? ["success" => true, "message" => "Certificato '$cn' importato con successo!"] : ["success" => false, "message" => "Errore nel salvataggio."];
    }
}

// Gestione POST per l'importazione CA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_ca'])) {
    if (!empty($_FILES['ca_cert']['tmp_name'])) {
        $certData = file_get_contents($_FILES['ca_cert']['tmp_name']);
        $keyData = !empty($_FILES['ca_key']['tmp_name']) ? file_get_contents($_FILES['ca_key']['tmp_name']) : null;

        $res = SSLImporter::importCA($certData, $keyData);
        $msg = $res['message'];
        $type = $res['success'] ? 'success' : 'danger';
    } else {
        $msg = 'File certificato CA obbligatorio.'; $type = 'danger';
    }
}

// Gestione POST per l'importazione Certificato Foglia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_cert'])) {
    if (!empty($_FILES['cert_file']['tmp_name'])) {
        $caId = intval($_POST['ca_id']);
        $sanString = $_POST['san_dns'] ?? '';
        $certData = file_get_contents($_FILES['cert_file']['tmp_name']);
        $keyData = !empty($_FILES['cert_key']['tmp_name']) ? file_get_contents($_FILES['cert_key']['tmp_name']) : null;

        $res = SSLImporter::importCertificate($caId, $certData, $keyData, $sanString);
        $msg = $res['message'];
        $type = $res['success'] ? 'success' : 'danger';
    } else {
        $msg = 'File certificato obbligatorio.'; $type = 'danger';
    }
}

// Recupera le CA disponibili per l'associazione del certificato foglia
$cas = $pdo->query("SELECT id, common_name FROM cas ORDER BY common_name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php require_once 'includes/head.php'; ?>
    <title>AegisCA | Import SSL</title>
</head>
<body>
    <?php include 'includes/topbar.php'; ?>
    
    <div class="container">
        <?php if($msg): ?><div class="alert alert-<?=$type?>"><?=htmlspecialchars($msg)?></div><?php endif; ?>

        <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 20px;">
            
            <div class="panel">
                <h3>Importa Esistente Certificate Authority (Root CA)</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Certificato della CA (.crt, .pem, .cer) *</label>
                        <input type="file" name="ca_cert" accept=".crt,.pem,.cer" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Chiave Privata della CA (.key, .pem) <small style="color: #64748b;">(Opzionale)</small></label>
                        <input type="file" name="ca_key" accept=".key,.pem">
                    </div>
                    <button type="submit" name="import_ca" class="btn">Importa Root CA</button>
                </form>
            </div>

            <div class="panel">
                <h3>Importa Esistente Certificato Foglia / End-Entity</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Associa alla CA firmataria *</label>
                        <select name="ca_id" required>
                            <option value="" disabled selected>-- Seleziona la CA --</option>
                            <?php foreach($cas as $ca): ?>
                                <option value="<?=$ca['id']?>"><?=htmlspecialchars($ca['common_name'])?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Certificato (.crt, .pem) *</label>
                        <input type="file" name="cert_file" accept=".crt,.pem" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Chiave Privata (.key) <small style="color: #64748b;">(Opzionale)</small></label>
                        <input type="file" name="cert_key" accept=".key">
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Subject Alternative Names (SAN) <small style="color: #64748b;">(Opzionale, separati da virgola)</small></label>
                        <input type="text" name="san_dns" placeholder="esempio.local, *.esempio.local, 192.168.1.100">
                    </div>
                    <button type="submit" name="import_cert" class="btn" style="background-color: #0284c7;">Importa Certificato</button>
                </form>
            </div>

        </div>
    </div>
</body>
</html>