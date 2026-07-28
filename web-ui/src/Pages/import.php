<?php
// src/Pages/import.php
// Nota: Auth, $pdo e il layout sono gestiti centralmente dal PageController.

require_once ROOT_PATH . 'src/Classes/ImportEngine.php';

$msg = ''; 
$type = '';

global $pdo;

// Gestione POST per l'importazione CA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_ca'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $msg = 'Richiesta non valida o token CSRF scaduto.';
        $type = 'danger';
    } else {
        if (!empty($_FILES['ca_cert']['tmp_name'])) {
            $certData = file_get_contents($_FILES['ca_cert']['tmp_name']);
            $keyData = !empty($_FILES['ca_key']['tmp_name']) ? file_get_contents($_FILES['ca_key']['tmp_name']) : null;

            $res = ImportEngine::importCA($certData, $keyData);
            $msg = $res['message'];
            $type = $res['success'] ? 'success' : 'danger';
        } else {
            $msg = 'File certificato CA obbligatorio.'; 
            $type = 'danger';
        }
    }
}

// Gestione POST per l'importazione Certificato Foglia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_cert'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $msg = 'Richiesta non valida o token CSRF scaduto.';
        $type = 'danger';
    } else {
        if (!empty($_FILES['cert_file']['tmp_name'])) {
            $caId = intval($_POST['ca_id']);
            $sanString = $_POST['san_dns'] ?? '';
            $certData = file_get_contents($_FILES['cert_file']['tmp_name']);
            $keyData = !empty($_FILES['cert_key']['tmp_name']) ? file_get_contents($_FILES['cert_key']['tmp_name']) : null;

            $res = ImportEngine::importCertificate($caId, $certData, $keyData, $sanString);
            $msg = $res['message'];
            $type = $res['success'] ? 'success' : 'danger';
        } else {
            $msg = 'File certificato obbligatorio.'; 
            $type = 'danger';
        }
    }
}

$cas = $pdo->query("SELECT id, common_name FROM cas ORDER BY common_name ASC")->fetchAll();
?>

<div class="container">
    <?php if($msg): ?>
        <div class="alert alert-<?=$type?>"><?=htmlspecialchars($msg)?></div>
    <?php endif; ?>

    <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 20px;">
        
        <div class="panel">
            <h3>Importa Esistente Certificate Authority (Root CA)</h3>
            <form method="POST" enctype="multipart/form-data">
                <!-- Campo Token CSRF -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Certificato della CA (.crt, .pem, .cer) *</label>
                    <input type="file" name="ca_cert" accept=".crt,.pem,.cer" required>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Chiave Privata della CA (.key, .pem) <small style="color: var(--text-muted);">(Opzionale)</small></label>
                    <input type="file" name="ca_key" accept=".key,.pem">
                </div>
                <button type="submit" name="import_ca" class="btn">Importa Root CA</button>
            </form>
        </div>

        <div class="panel">
            <h3>Importa Esistente Certificato Foglia / End-Entity</h3>
            <form method="POST" enctype="multipart/form-data">
                <!-- Campo Token CSRF -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

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
                    <label>Chiave Privata (.key) <small style="color: var(--text-muted);">(Opzionale)</small></label>
                    <input type="file" name="cert_key" accept=".key">
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Subject Alternative Names (SAN) <small style="color: var(--text-muted);">(Opzionale, separati da virgola)</small></label>
                    <input type="text" name="san_dns" placeholder="esempio.local, *.esempio.local, 192.168.1.100">
                </div>
                <button type="submit" name="import_cert" class="btn" style="background-color: #0284c7;">Importa Certificato</button>
            </form>
        </div>

    </div>
</div>