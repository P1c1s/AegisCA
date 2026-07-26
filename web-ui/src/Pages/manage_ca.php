<?php
// src/Pages/manage_ca.php
// Nota: Auth, SslEngine ($pdo) e il layout sono già caricati dal PageController.

require_once ROOT_PATH . 'src/Classes/SslEngine.php';

$msg = ''; 
$type = '';

global $pdo;

// Eliminazione CA
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM cas WHERE id = ?");
    if ($stmt->execute([$_GET['delete']])) {
        $msg = 'Certificate Authority rimossa con successo.'; 
        $type = 'success';
    }
}

// Creazione CA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_ca'])) {
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
            $msg = 'CA Generata con Successo!'; 
            $type = 'success';
        } else {
            $msg = 'Errore imprevisto durante la generazione della CA.'; 
            $type = 'danger';
        }
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $type = 'danger';
    }
}

$cas = $pdo->query("SELECT * FROM cas ORDER BY created_at DESC")->fetchAll();
?>

<div class="container">
    <?php if($msg): ?>
        <div class="alert alert-<?=$type?>"><?=htmlspecialchars($msg)?></div>
    <?php endif; ?>

    <div class="panel">
        <h3>Crea Nuova Local Certificate Authority (Root CA)</h3>
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Country (C)</label>
                    <input type="text" name="country" placeholder="IT" minlength="2" maxlength="2" required>
                </div>
                <div class="form-group">
                    <label>State/Province (ST)</label>
                    <input type="text" name="state" placeholder="Lazio" required>
                </div>
                <div class="form-group">
                    <label>Locality (L)</label>
                    <input type="text" name="locality" placeholder="Roma" required>
                </div>
                
                <div class="form-group">
                    <label for="key_type">Algoritmo Chiave</label>
                    <select name="key_type" id="key_type" required>
                        <option value="rsa" selected>RSA (Standard, compatibilità globale)</option>
                        <option value="ecc">ECC (Moderno, ultra-veloce ed efficiente)</option>
                    </select>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Organization (O)</label>
                    <input type="text" name="organization" placeholder="HomeLab" required>
                </div>
                <div class="form-group">
                    <label>Organizational Unit (OU)</label>
                    <input type="text" name="org_unit" placeholder="IT" required>
                </div>
                <div class="form-group">
                    <label>Common Name (CN)</label>
                    <input type="text" name="common_name" placeholder="Mia Root CA Local" required>
                </div>

                <div class="form-group" id="rsa_options_wrapper">
                    <label for="key_bits_rsa">Lunghezza Chiave (RSA):</label>
                    <select name="key_bits_rsa" id="key_bits_rsa">
                        <option value="4096" selected>4096 bit (Consigliata per Root CA)</option>
                        <option value="3072">3072 bit (Bilanciata)</option>
                        <option value="2048">2048 bit (Standard minimo)</option>
                    </select>
                </div>

                <div class="form-group" id="ecc_options_wrapper" style="display: none;">
                    <label for="key_curve_ecc">Tipo di Curva (ECC):</label>
                    <select name="key_curve_ecc" id="key_curve_ecc">
                        <option value="prime256v1" selected>prime256v1 (NIST P-256 - Standard)</option>
                        <option value="secp384r1">secp384r1 (NIST P-384 - Alta Sicurezza)</option>
                        <option value="secp521r1">secp521r1 (NIST P-521 - Paranoico)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Validità (Giorni)</label>
                    <input type="number" name="days" value="3650" required>
                </div>
            </div>

            <!-- Campo Password sempre visibile e opzionale -->
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="ca_password">Password CA (Opzionale)</label>
                <input type="password" name="ca_password" id="ca_password" placeholder="Lascia vuoto se non vuoi proteggere la chiave con password">
                <small style="color: var(--text-muted); display: block; margin-top: 4px;">
                    Nota: Se inserisci una password, ti verrà richiesta ogni volta che dovrai emettere un certificato con questa CA.
                </small>
            </div>

            <button type="submit" name="create_ca" class="btn">Genera Root CA</button>
        </form>
    </div>

    <div class="panel">
        <h3>Certificate Authorities Configurate</h3>
        <table>
            <thead>
                <tr>
                    <th>Common Name</th>
                    <th>Soggetto Completo</th>
                    <th>Creazione</th>
                    <th>Scadenza</th>
                    <th>Algoritmo / Robustezza</th>
                    <th>Stato</th>
                    <th>Azioni</th>
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
                            <?=$isExpired ? 'Scaduta' : 'Attiva'?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?action=download&type=ca_cert&id=<?=$ca['id']?>" class="btn btn-sm">Esporta CRT</a>
                        <a href="index.php?action=download&type=ca_key&id=<?=$ca['id']?>" class="btn btn-sm" style="background-color:#64748b;">Esporta KEY</a>
                        <a href="index.php?page=manage_ca&delete=<?=$ca['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questa CA e invalidare i certificati emessi?')">Elimina</a>
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