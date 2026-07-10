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

    $keyBits = isset($_POST['key_bits']) ? intval($_POST['key_bits']) : 4096;
    $encryptKey = isset($_POST['protect_with_password']);
    $caPassword = ($encryptKey && !empty($_POST['ca_password'])) ? $_POST['ca_password'] : null;

    try {
        // Usiamo la classe SslEngine con la capitalizzazione corretta
        if (SslEngine::createCA($dnData, intval($_POST['days']), $keyBits, $caPassword)) {
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
                    <label for="key_bits">Lunghezza Chiave Privata (RSA):</label>
                    <select name="key_bits" id="key_bits" required>
                        <option value="4096" selected>4096 bit (Consigliata per Root CA)</option>
                        <option value="3072">3072 bit (Bilanciata)</option>
                        <option value="2048">2048 bit (Standard minimo)</option>
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
                <div class="form-group">
                    <label>Validità (Giorni)</label>
                    <input type="number" name="days" value="3650" required>
                </div>
            </div>

            <div class="form-group" style="margin: 15px 0;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="protect_with_password" id="protect_with_password" value="1">
                    <strong>Proteggi la chiave privata di questa CA con una password</strong>
                </label>
            </div>

            <div id="password_secure_block" style="display: none; background: var(--bg-dark); padding: 15px; border-radius: 6px; border: 1px solid var(--border); margin-bottom: 20px;">
                <div class="form-group" style="margin: 0;">
                    <label for="ca_password" style="color: var(--text-main); font-weight: bold;">Password della CA *</label>
                    <input type="password" name="ca_password" id="ca_password" placeholder="Inserisci una passphrase robusta">
                    <small style="color: var(--text-muted); display: block; margin-top: 5px;">
                        Nota: Questa password ti verrà richiesta ogni volta che dovrai rilasciare un certificato usando questa CA.
                    </small>
                </div>
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
                    <th>Robustezza</th>
                    <th>Stato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cas as $ca): 
                    $isExpired = strtotime($ca['valid_to']) < time();
                ?>
                <tr>
                    <td><strong><?=htmlspecialchars($ca['common_name'])?></strong></td>
                    <td><small>/C=<?=htmlspecialchars($ca['subject_country'] ?? '')?>/ST=<?=htmlspecialchars($ca['subject_state'] ?? '')?>/L=<?=htmlspecialchars($ca['subject_locality'] ?? '')?>/O=<?=htmlspecialchars($ca['subject_organization'] ?? '')?>/OU=<?=htmlspecialchars($ca['subject_org_unit'] ?? '')?></small></td>
                    <td><?=htmlspecialchars($ca['created_at'])?></td>
                    <td><?=htmlspecialchars($ca['valid_to'])?></td>
                    <td><span class="badge" style="background-color: #475569; color: #fff;"><?=intval($ca['key_bits'])?> bit</span></td>
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
document.getElementById('protect_with_password').addEventListener('change', function() {
    const passwordBlock = document.getElementById('password_secure_block');
    const passwordInput = document.getElementById('ca_password');
    
    if (this.checked) {
        passwordBlock.style.display = 'block';
        passwordInput.setAttribute('required', 'required');
    } else {
        passwordBlock.style.display = 'none';
        passwordInput.removeAttribute('required');
        passwordInput.value = '';
    }
});
</script>