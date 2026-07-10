<?php
// src/Pages/manage_certs.php
// Nota: Auth, SslEngine ($pdo) e il layout di base sono già gestiti dal PageController.

require_once ROOT_PATH . 'src/Classes/SslEngine.php';

$msg = ''; 
$type = '';

global $pdo;

// Eliminazione Certificato
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM certificates WHERE id = ?");
    if ($stmt->execute([$_GET['delete']])) {
        $msg = 'Certificato rimosso dal sistema.'; 
        $type = 'success';
    }
}

// Creazione Certificato
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_cert'])) {
    $dnData = [
        'c'  => $_POST['country'] ?? '',
        'st' => $_POST['state'] ?? '',
        'l'  => $_POST['locality'] ?? '',
        'o'  => $_POST['organization'] ?? '',
        'ou' => $_POST['org_unit'] ?? '',
        'cn' => $_POST['common_name'] ?? ''
    ];

    $keyBits = isset($_POST['key_bits']) ? intval($_POST['key_bits']) : 2048;
    $caPassword = !empty($_POST['ca_password']) ? $_POST['ca_password'] : null;

    try {
        // Utilizziamo SslEngine secondo lo standard rinominato
        if (SslEngine::createCertificate($_POST['ca_id'], $dnData, $_POST['san'] ?? '', intval($_POST['days']), $keyBits, $caPassword)) {
            $msg = 'Certificato SSL emesso e firmato con successo!'; 
            $type = 'success';
        } else {
            $msg = 'Errore imprevisto durante la creazione del certificato.'; 
            $type = 'danger';
        }
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $type = 'danger';
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
        <h3>Emetti Nuovo Certificato SSL</h3>
        <form method="POST" id="certForm">
            <div class="form-grid">
                <div class="form-group">
                    <label>Autorità di Firma (CA)</label>
                    <select name="ca_id" required>
                        <option value="">-- Seleziona CA Autorizzata --</option>
                        <?php foreach($cas as $ca): ?>
                            <option value="<?=$ca['id']?>"><?=htmlspecialchars($ca['common_name'])?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="ca_password">Password Sblocco Chiave CA</label>
                    <input type="password" name="ca_password" id="ca_password" placeholder="Lascia vuoto se la CA non ha password" autocomplete="new-password">
                </div>

                <div class="form-group">
                    <label for="key_bits">Lunghezza Chiave Privata (RSA):</label>
                    <select name="key_bits" id="key_bits" required>
                        <option value="4096">4096 bit (Massima sicurezza)</option>
                        <option value="3072">3072 bit (Bilanciata)</option>
                        <option value="2048" selected>2048 bit (Standard consigliato per certificati)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Common Name (CN)</label>
                    <input type="text" name="common_name" placeholder="freshrss.hole" required>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Validità (Giorni)</label>
                    <input type="number" name="days" value="825" max="825" required>
                </div>
                <div class="form-group">
                    <label>Country (C)</label>
                    <input type="text" name="country" placeholder="IT" maxlength="2" required>
                </div>
                <div class="form-group">
                    <label>State (ST)</label>
                    <input type="text" name="state" placeholder="Lazio" required>
                </div>
                <div class="form-group">
                    <label>Locality (L)</label>
                    <input type="text" name="locality" placeholder="Roma" required>
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
            </div>

            <div class="form-group full-width" style="margin-bottom:1rem;">
                <label>Subject Alternative Names (SAN) - Separati da virgola (IP o DNS)</label>
                <input type="text" name="san" placeholder="*.freshrss.hole, 192.168.1.50, freshrss.hole">
            </div>

            <button type="submit" name="create_cert" class="btn">Rilascia Certificato</button>
        </form>
    </div>

    <div class="panel">
        <h3>Certificati SSL Emessi</h3>
        <table>
            <thead>
                <tr>
                    <th>Common Name (Dominio)</th>
                    <th>Firmato Da</th>
                    <th>SAN</th>
                    <th>Scadenza</th>
                    <th>Robustezza</th>
                    <th>Stato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($certs as $cert): 
                    $isExpired = strtotime($cert['valid_to']) < time();
                ?>
                <tr>
                    <td><strong><?=htmlspecialchars($cert['common_name'])?></strong></td>
                    <td><span style="color:var(--accent);"><?=htmlspecialchars($cert['ca_name'])?></span></td>
                    <td><small><?=htmlspecialchars($cert['san_dns'] ?? '')?></small></td>
                    <td><?=htmlspecialchars($cert['valid_to'])?></td>
                    <td><span class="badge" style="background-color: #475569; color: #fff;"><?=intval($cert['key_bits'])?> bit</span></td>
                    <td>
                        <span class="badge <?=$isExpired ? 'badge-danger' : 'badge-success'?>">
                            <?=$isExpired ? 'Scaduto' : 'Attiva'?>
                        </span>
                    </td>
                    <td>
                        <a href="index.php?action=download&type=cert_cert&id=<?=$cert['id']?>" class="btn btn-sm">Esporta CRT</a>
                        <a href="index.php?action=download&type=cert_key&id=<?=$cert['id']?>" class="btn btn-sm" style="background-color:#64748b;">Esporta KEY</a>
                        <a href="index.php?page=manage_certs&delete=<?=$cert['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Vuoi cancellare il certificato?')">Elimina</a>
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
</script>