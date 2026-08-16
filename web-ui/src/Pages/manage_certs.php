<?php
// src/Pages/manage_certs.php
// Nota: Auth, SslEngine ($pdo) e il layout di base sono già gestiti dal PageController.

require_once ROOT_PATH . 'src/Classes/SslEngine.php';

$msg = ''; 
$type = '';

global $pdo;

// Eliminazione Certificato (Convertita in POST + CSRF per sicurezza)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cert_id'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $msg = __('manage_certs_error_csrf_invalid', 'Invalid request or session expired.');
        $type = 'danger';
    } else {
        $stmt = $pdo->prepare("DELETE FROM certificates WHERE id = ?");
        if ($stmt->execute([$_POST['delete_cert_id']])) {
            $msg = __('manage_certs_msg_deleted_success', 'Certificate removed from the system.'); 
            $type = 'success';
        }
    }
}

// Creazione Certificato
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_cert'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $msg = __('manage_certs_error_csrf_invalid', 'Invalid request or session expired.');
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
        $keySpec = ($keyType === 'ecc') ? ($_POST['key_curve_ecc'] ?? 'prime256v1') : ($_POST['key_bits_rsa'] ?? 2048);
        $caPassword = !empty($_POST['ca_password']) ? $_POST['ca_password'] : null;

        try {
            // Passiamo $keyType e $keySpec al metodo aggiornato di SslEngine
            if (SslEngine::createCertificate($_POST['ca_id'], $dnData, $_POST['san'] ?? '', intval($_POST['days']), $keyType, $keySpec, $caPassword)) {
                $msg = __('manage_certs_msg_created_success', 'SSL Certificate issued and signed successfully!'); 
                $type = 'success';
            } else {
                $msg = __('manage_certs_msg_created_error', 'Unexpected error occurred during certificate creation.'); 
                $type = 'danger';
            }
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $type = 'danger';
        }
    }
}

$cas = $pdo->query("SELECT id, common_name FROM cas ORDER BY common_name ASC")->fetchAll();
$certs = $pdo->query("SELECT c.*, ca.common_name as ca_name FROM certificates c JOIN cas ca ON c.ca_id = ca.id ORDER BY c.common_name ASC")->fetchAll();
?>

<div class="container">
    <?php if($msg): ?>
        <div class="alert alert-<?=$type?>"><?=htmlspecialchars($msg)?></div>
    <?php endif; ?>

    <div class="panel">
        <h3><?= __('manage_certs_create_title', 'Issue New SSL Certificate') ?></h3>
        <form method="POST" id="certForm">
            <!-- Campo Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

            <div class="form-grid">
                <div class="form-group">
                    <label><?= __('manage_certs_label_ca', 'Signing Authority (CA)') ?></label>
                    <select name="ca_id" required>
                        <option value=""><?= __('manage_certs_select_ca_placeholder', '-- Select Authorized CA --') ?></option>
                        <?php foreach($cas as $ca): ?>
                            <option value="<?=$ca['id']?>"><?=htmlspecialchars($ca['common_name'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="ca_password"><?= __('manage_certs_label_ca_password', 'CA Key Unlock Password') ?></label>
                    <input type="password" name="ca_password" id="ca_password" placeholder="<?= __('manage_certs_ph_ca_password', 'Leave blank if CA has no password') ?>" autocomplete="new-password">
                </div>

                <div class="form-group">
                    <label for="key_type"><?= __('manage_certs_label_key_type', 'Key Algorithm') ?></label>
                    <select name="key_type" id="key_type" required>
                        <option value="rsa" selected><?= __('manage_certs_option_rsa', 'RSA (Recommended for legacy servers)') ?></option>
                        <option value="ecc"><?= __('manage_certs_option_ecc', 'ECC / ECDSA (Optimized for HomeLab/Fast)') ?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label><?= __('manage_certs_label_common_name', 'Common Name (CN)') ?></label>
                    <input type="text" name="common_name" placeholder="<?= __('manage_certs_ph_common_name', 'freshrss.hole') ?>" required>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group" id="rsa_options_wrapper">
                    <label for="key_bits_rsa"><?= __('manage_certs_label_rsa_length', 'Key Length (RSA):') ?></label>
                    <select name="key_bits_rsa" id="key_bits_rsa">
                        <option value="4096"><?= __('manage_certs_rsa_4096', '4096 bit (Maximum security)') ?></option>
                        <option value="3072"><?= __('manage_certs_rsa_3072', '3072 bit (Balanced)') ?></option>
                        <option value="2048" selected><?= __('manage_certs_rsa_2048', '2048 bit (Recommended standard)') ?></option>
                    </select>
                </div>

                <div class="form-group" id="ecc_options_wrapper" style="display: none;">
                    <label for="key_curve_ecc"><?= __('manage_certs_label_ecc_curve', 'Curve Type (ECC):') ?></label>
                    <select name="key_curve_ecc" id="key_curve_ecc">
                        <option value="prime256v1" selected><?= __('manage_certs_ecc_p256', 'prime256v1 (NIST P-256 - Standard & Fast)') ?></option>
                        <option value="secp384r1"><?= __('manage_certs_ecc_p384', 'secp384r1 (NIST P-384 - High Security)') ?></option>
                        <option value="secp521r1"><?= __('manage_certs_ecc_p521', 'secp521r1 (NIST P-521 - Paranoid)') ?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label><?= __('manage_certs_label_validity', 'Validity (Days)') ?></label>
                    <input type="number" name="days" value="825" max="825" required>
                </div>
                <div class="form-group">
                    <label><?= __('manage_certs_label_country', 'Country (C)') ?></label>
                    <input type="text" name="country" placeholder="<?= __('manage_certs_ph_country', 'IT') ?>" maxlength="2" required>
                </div>
                <div class="form-group">
                    <label><?= __('manage_certs_label_state', 'State (ST)') ?></label>
                    <input type="text" name="state" placeholder="<?= __('manage_certs_ph_state', 'Lazio') ?>" required>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label><?= __('manage_certs_label_locality', 'Locality (L)') ?></label>
                    <input type="text" name="locality" placeholder="<?= __('manage_certs_ph_locality', 'Rome') ?>" required>
                </div>
                <div class="form-group">
                    <label><?= __('manage_certs_label_organization', 'Organization (O)') ?></label>
                    <input type="text" name="organization" placeholder="<?= __('manage_certs_ph_organization', 'HomeLab') ?>" required>
                </div>
                <div class="form-group">
                    <label><?= __('manage_certs_label_org_unit', 'Organizational Unit (OU)') ?></label>
                    <input type="text" name="org_unit" placeholder="<?= __('manage_certs_ph_org_unit', 'IT') ?>" required>
                </div>
            </div>

            <div class="form-group full-width" style="margin-bottom:1rem;">
                <label><?= __('manage_certs_label_san', 'Subject Alternative Names (SAN) - Comma separated (IP or DNS)') ?></label>
                <input type="text" name="san" placeholder="<?= __('manage_certs_ph_san', '*.freshrss.hole, 192.168.1.50, freshrss.hole') ?>">
            </div>

            <button type="submit" name="create_cert" class="btn"><?= __('manage_certs_btn_issue', 'Issue Certificate') ?></button>
        </form>
    </div>

    <div class="panel">
        <h3><?= __('manage_certs_list_title', 'Issued SSL Certificates') ?></h3>
        <table>
            <thead>
                <tr>
                    <th><?= __('manage_certs_th_common_name', 'Common Name (Domain)') ?></th>
                    <th><?= __('manage_certs_th_signed_by', 'Signed By') ?></th>
                    <th><?= __('manage_certs_th_san', 'SAN') ?></th>
                    <th><?= __('manage_certs_th_expires_at', 'Expiration') ?></th>
                    <th><?= __('manage_certs_th_algorithm', 'Algorithm / Strength') ?></th> 
                    <th><?= __('manage_certs_th_status', 'Status') ?></th>
                    <th><?= __('manage_certs_th_actions', 'Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($certs as $cert): 
                    $isExpired = strtotime($cert['valid_to']) < time();
                    $algo = strtoupper($cert['key_type'] ?? 'rsa');
                ?>
                <tr>
                    <td><strong><a href="http://<?=htmlspecialchars($cert['common_name'])?>"><?=htmlspecialchars($cert['common_name'])?></a></strong></td>
                    <td><span style="color:var(--accent);"><?=htmlspecialchars($cert['ca_name'])?></span></td>
                    <td><small><?=htmlspecialchars($cert['san_dns'] ?? '')?></small></td>
                    <td><?=htmlspecialchars($cert['valid_to'])?></td>
                    <td>
                        <span class="badge" style="background-color: #0284c7; color: #fff; font-weight: bold; margin-right: 5px;"><?=$algo?></span>
                        <span class="badge" style="background-color: #475569; color: #fff;"><?=intval($cert['key_bits'])?> bit</span>
                    </td>
                    <td>
                        <span class="badge <?=$isExpired ? 'badge-danger' : 'badge-success'?>">
                            <?=$isExpired ? __('manage_certs_status_expired', 'Expired') : __('manage_certs_status_active', 'Active')?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?action=download&type=cert_cert&id=<?=$cert['id']?>" class="btn btn-sm"><?= __('manage_certs_btn_export_crt', 'Export CRT') ?></a>
                        <a href="index.php?action=download&type=cert_key&id=<?=$cert['id']?>" class="btn btn-sm" style="background-color:#64748b;"><?= __('manage_certs_btn_export_key', 'Export KEY') ?></a>
                        
                        <!-- Form di eliminazione protetto da CSRF -->
                        <form method="POST" style="display:inline-block;" onsubmit="return confirm('<?= __('manage_certs_confirm_delete', 'Are you sure you want to delete this certificate?') ?>')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">
                            <input type="hidden" name="delete_cert_id" value="<?=$cert['id']?>">
                            <button type="submit" class="btn btn-sm btn-danger"><?= __('manage_certs_btn_delete', 'Delete') ?></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('certForm').addEventListener('submit', function() {
    const pwdInput = document.getElementById('ca_password');
    setTimeout(() => {
        pwdInput.value = '';
    }, 100);
});

// Gestione condizionale del pannello RSA bits vs ECC Curves
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