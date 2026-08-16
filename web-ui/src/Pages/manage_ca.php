<?php
// src/Pages/manage_ca.php
// Nota: Auth, SslEngine ($pdo) e il layout sono già caricati dal PageController.

require_once ROOT_PATH . 'src/Classes/SslEngine.php';

$msg = ''; 
$type = '';

global $pdo;

// Eliminazione CA (Convertita in POST + Token CSRF per massima sicurezza)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ca_id'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $msg = __('manage_ca_error_csrf_invalid', 'Invalid request or session expired.');
        $type = 'danger';
    } else {
        $stmt = $pdo->prepare("DELETE FROM cas WHERE id = ?");
        if ($stmt->execute([$_POST['delete_ca_id']])) {
            $msg = __('manage_ca_msg_deleted_success', 'Certificate Authority removed successfully.'); 
            $type = 'success';
        }
    }
}

// Creazione CA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_ca'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $msg = __('manage_ca_error_csrf_invalid', 'Invalid request or session expired.');
        $type = 'danger';
    } else {
        $dnData = [
            'c'  => $_POST['country'] ?? '',
            'st' => $_POST['state'] ?? '',
            'l'  => $_POST['locality'] ?? '',
            'o'  => $_POST['organization'] ?? '',
            'ou' => $_POST['org_unit'] ?? '',
            'cn' => $_POST['common_name'] ?? ''
        ];

        // Recupero dinamico del tipo di algoritmo
        $keyType = isset($_POST['key_type']) ? strtolower($_POST['key_type']) : 'rsa';
        
        // Assegnazione della specifica (Curva per ECC o Bit per RSA)
        $keySpec = ($keyType === 'ecc') ? ($_POST['key_curve_ecc'] ?? 'prime256v1') : ($_POST['key_bits_rsa'] ?? 4096);
        
        // Gestione Password Opzionale: se vuota o composta da soli spazi rimane null
        $caPassword = !empty(trim($_POST['ca_password'] ?? '')) ? trim($_POST['ca_password']) : null;

        try {
            // Passiamo $keyType e $keySpec alla classe SslEngine
            if (SslEngine::createCA($dnData, intval($_POST['days']), $keyType, $keySpec, $caPassword)) {
                $msg = __('manage_ca_msg_created_success', 'CA Generated Successfully!'); 
                $type = 'success';
            } else {
                $msg = __('manage_ca_msg_created_error', 'Unexpected error occurred during CA generation.'); 
                $type = 'danger';
            }
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $type = 'danger';
        }
    }
}

$cas = $pdo->query("SELECT * FROM cas ORDER BY created_at DESC")->fetchAll();
?>

<div class="container">
    <?php if($msg): ?>
        <div class="alert alert-<?=$type?>"><?=htmlspecialchars($msg)?></div>
    <?php endif; ?>

    <div class="panel">
        <h3><?= __('manage_ca_create_title', 'Create New Local Certificate Authority (Root CA)') ?></h3>
        <form method="POST">
            <!-- Campo Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

            <div class="form-grid">
                <div class="form-group">
                    <label><?= __('manage_ca_label_country', 'Country (C)') ?></label>
                    <input type="text" name="country" placeholder="<?= __('manage_ca_ph_country', 'IT') ?>" minlength="2" maxlength="2" required>
                </div>
                <div class="form-group">
                    <label><?= __('manage_ca_label_state', 'State/Province (ST)') ?></label>
                    <input type="text" name="state" placeholder="<?= __('manage_ca_ph_state', 'Lazio') ?>" required>
                </div>
                <div class="form-group">
                    <label><?= __('manage_ca_label_locality', 'Locality (L)') ?></label>
                    <input type="text" name="locality" placeholder="<?= __('manage_ca_ph_locality', 'Rome') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="key_type"><?= __('manage_ca_label_key_type', 'Key Algorithm') ?></label>
                    <select name="key_type" id="key_type" required>
                        <option value="rsa" selected><?= __('manage_ca_option_rsa', 'RSA (Standard, global compatibility)') ?></option>
                        <option value="ecc"><?= __('manage_ca_option_ecc', 'ECC (Modern, ultra-fast & efficient)') ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label><?= __('manage_ca_label_organization', 'Organization (O)') ?></label>
                    <input type="text" name="organization" placeholder="<?= __('manage_ca_ph_organization', 'HomeLab') ?>" required>
                </div>
                <div class="form-group">
                    <label><?= __('manage_ca_label_org_unit', 'Organizational Unit (OU)') ?></label>
                    <input type="text" name="org_unit" placeholder="<?= __('manage_ca_ph_org_unit', 'IT') ?>" required>
                </div>
                <div class="form-group">
                    <label><?= __('manage_ca_label_common_name', 'Common Name (CN)') ?></label>
                    <input type="text" name="common_name" placeholder="<?= __('manage_ca_ph_common_name', 'My Local Root CA') ?>" required>
                </div>

                <div class="form-group" id="rsa_options_wrapper">
                    <label for="key_bits_rsa"><?= __('manage_ca_label_rsa_length', 'Key Length (RSA):') ?></label>
                    <select name="key_bits_rsa" id="key_bits_rsa">
                        <option value="4096" selected><?= __('manage_ca_rsa_4096', '4096 bit (Recommended for Root CA)') ?></option>
                        <option value="3072"><?= __('manage_ca_rsa_3072', '3072 bit (Balanced)') ?></option>
                        <option value="2048"><?= __('manage_ca_rsa_2048', '2048 bit (Minimum standard)') ?></option>
                    </select>
                </div>

                <div class="form-group" id="ecc_options_wrapper" style="display: none;">
                    <label for="key_curve_ecc"><?= __('manage_ca_label_ecc_curve', 'Curve Type (ECC):') ?></label>
                    <select name="key_curve_ecc" id="key_curve_ecc">
                        <option value="prime256v1" selected><?= __('manage_ca_ecc_p256', 'prime256v1 (NIST P-256 - Standard)') ?></option>
                        <option value="secp384r1"><?= __('manage_ca_ecc_p384', 'secp384r1 (NIST P-384 - High Security)') ?></option>
                        <option value="secp521r1"><?= __('manage_ca_ecc_p521', 'secp521r1 (NIST P-521 - Paranoid)') ?></option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><?= __('manage_ca_label_validity', 'Validity (Days)') ?></label>
                    <input type="number" name="days" value="3650" required>
                </div>
            </div>

            <!-- Campo Password sempre visibile e opzionale -->
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="ca_password"><?= __('manage_ca_label_password', 'CA Password (Optional)') ?></label>
                <input type="password" name="ca_password" id="ca_password" placeholder="<?= __('manage_ca_placeholder_password', 'Leave blank if you do not want to protect key with password') ?>">
                <small style="color: var(--text-muted); display: block; margin-top: 4px;">
                    <?= __('manage_ca_help_password', 'Note: If set, you will be prompted for this password whenever issuing certificates with this CA.') ?>
                </small>
            </div>

            <button type="submit" name="create_ca" class="btn"><?= __('manage_ca_btn_create', 'Generate Root CA') ?></button>
        </form>
    </div>

    <div class="panel">
        <h3><?= __('manage_ca_list_title', 'Configured Certificate Authorities') ?></h3>
        <table>
            <thead>
                <tr>
                    <th><?= __('manage_ca_th_common_name', 'Common Name') ?></th>
                    <th><?= __('manage_ca_th_full_subject', 'Full Subject') ?></th>
                    <th><?= __('manage_ca_th_created_at', 'Created At') ?></th>
                    <th><?= __('manage_ca_th_expires_at', 'Expiration') ?></th>
                    <th><?= __('manage_ca_th_algorithm', 'Algorithm / Strength') ?></th>
                    <th><?= __('manage_ca_th_status', 'Status') ?></th>
                    <th><?= __('manage_ca_th_actions', 'Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cas as $ca): 
                    $isExpired = strtotime($ca['valid_to']) < time();
                    $algo = strtoupper($ca['key_type'] ?? 'rsa');
                ?>
                <tr>
                    <td><strong><?=htmlspecialchars($ca['common_name'])?></strong></td>
                    <td><small>/C=<?=htmlspecialchars($ca['subject_country'] ?? '')?>/ST=<?=htmlspecialchars($ca['subject_state'] ?? '')?>/L=<?=htmlspecialchars($ca['subject_locality'] ?? '')?>/O=<?=htmlspecialchars($ca['subject_organization'] ?? '')?>/OU=<?=htmlspecialchars($ca['subject_org_unit'] ?? '')?></small></td>
                    <td><?=htmlspecialchars($ca['created_at'])?></td>
                    <td><?=htmlspecialchars($ca['valid_to'])?></td>
                    <td>
                        <span class="badge" style="background-color: #0284c7; color: #fff; font-weight: bold; margin-right: 5px;"><?=$algo?></span>
                        <span class="badge" style="background-color: #475569; color: #fff;"><?=intval($ca['key_bits'])?> bit</span>
                    </td>
                    <td>
                        <span class="badge <?=$isExpired ? 'badge-danger' : 'badge-success'?>">
                            <?=$isExpired ? __('manage_ca_status_expired', 'Expired') : __('manage_ca_status_active', 'Active')?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?action=download&type=ca_cert&id=<?=$ca['id']?>" class="btn btn-sm"><?= __('manage_ca_btn_export_crt', 'Export CRT') ?></a>
                        <a href="index.php?action=download&type=ca_key&id=<?=$ca['id']?>" class="btn btn-sm" style="background-color:#64748b;"><?= __('manage_ca_btn_export_key', 'Export KEY') ?></a>
                        
                        <!-- Form Sicuro per l'eliminazione tramite POST + CSRF -->
                        <form method="POST" style="display:inline-block;" onsubmit="return confirm('<?= __('manage_ca_confirm_delete', 'Are you sure you want to delete this CA and invalidate all issued certificates?') ?>')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">
                            <input type="hidden" name="delete_ca_id" value="<?=$ca['id']?>">
                            <button type="submit" class="btn btn-sm btn-danger"><?= __('manage_ca_btn_delete', 'Delete') ?></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Scambio condizionale dei selettori (Bit RSA vs Curve ECC)
document.getElementById('key_type').addEventListener('change', function() {
    const rsaWrapper = document.getElementById('rsa_options_wrapper');
    const eccWrapper = document.getElementById('ecc_options_wrapper');
    
    if (this.value === 'ecc') {
        rsaWrapper.style.display = 'none';
        eccWrapper.style.display = 'block';
    } else {
        rsaWrapper.style.display = 'block';
        eccWrapper.style.display = 'none';
    }
});
</script>