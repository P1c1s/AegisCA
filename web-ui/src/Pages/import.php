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
        $msg = __('import_error_csrf_invalid', 'Invalid request or session expired.');
        $type = 'danger';
    } else {
        if (!empty($_FILES['ca_cert']['tmp_name'])) {
            $certData = file_get_contents($_FILES['ca_cert']['tmp_name']);
            $keyData = !empty($_FILES['ca_key']['tmp_name']) ? file_get_contents($_FILES['ca_key']['tmp_name']) : null;

            $res = ImportEngine::importCA($certData, $keyData);
            $msg = $res['message'];
            $type = $res['success'] ? 'success' : 'danger';
        } else {
            $msg = __('import_error_ca_cert_required', 'CA Certificate file is required.'); 
            $type = 'danger';
        }
    }
}

// Gestione POST per l'importazione Certificato Foglia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_cert'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $msg = __('import_error_csrf_invalid', 'Invalid request or session expired.');
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
            $msg = __('import_error_cert_required', 'Certificate file is required.'); 
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
        
        <!-- Form Importazione Root CA -->
        <div class="panel">
            <h3><?= __('import_ca_title', 'Import Existing Certificate Authority (Root CA)') ?></h3>
            <form method="POST" enctype="multipart/form-data">
                <!-- Campo Token CSRF -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?= __('import_ca_cert_label', 'CA Certificate (.crt, .pem, .cer) *') ?></label>
                    <input type="file" name="ca_cert" accept=".crt,.pem,.cer" required>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>
                        <?= __('import_ca_key_label', 'CA Private Key (.key, .pem)') ?> 
                        <small style="color: var(--text-muted);">(<?= __('import_optional', 'Optional') ?>)</small>
                    </label>
                    <input type="file" name="ca_key" accept=".key,.pem">
                </div>
                <button type="submit" name="import_ca" class="btn"><?= __('import_btn_ca', 'Import Root CA') ?></button>
            </form>
        </div>

        <!-- Form Importazione Certificato Foglia -->
        <div class="panel">
            <h3><?= __('import_cert_title', 'Import Existing End-Entity / Leaf Certificate') ?></h3>
            <form method="POST" enctype="multipart/form-data">
                <!-- Campo Token CSRF -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?= __('import_select_ca_label', 'Associate with Signing CA *') ?></label>
                    <select name="ca_id" required>
                        <option value="" disabled selected>-- <?= __('import_select_ca_placeholder', 'Select CA') ?> --</option>
                        <?php foreach($cas as $ca): ?>
                            <option value="<?=$ca['id']?>"><?=htmlspecialchars($ca['common_name'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?= __('import_cert_file_label', 'Certificate (.crt, .pem) *') ?></label>
                    <input type="file" name="cert_file" accept=".crt,.pem" required>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>
                        <?= __('import_cert_key_label', 'Private Key (.key)') ?> 
                        <small style="color: var(--text-muted);">(<?= __('import_optional', 'Optional') ?>)</small>
                    </label>
                    <input type="file" name="cert_key" accept=".key">
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>
                        <?= __('import_san_label', 'Subject Alternative Names (SAN)') ?> 
                        <small style="color: var(--text-muted);">(<?= __('import_san_help', 'Optional, comma separated') ?>)</small>
                    </label>
                    <input type="text" name="san_dns" placeholder="<?= __('import_san_placeholder', 'example.local, *.example.local, 192.168.1.100') ?>">
                </div>
                <button type="submit" name="import_cert" class="btn" style="background-color: #0284c7;"><?= __('import_btn_cert', 'Import Certificate') ?></button>
            </form>
        </div>

    </div>
</div>