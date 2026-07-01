<?php
require_once 'auth.php';
require_once 'ssl_engine.php';
Auth::check();

$msg = ''; $type = '';

// Eliminazione CA
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM cas WHERE id = ?");
    if ($stmt->execute([$_GET['delete']])) {
        $msg = 'Certificate Authority rimossa con successo.'; $type = 'success';
    }
}

// Creazione CA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_ca'])) {
    $dnData = [
        'c'  => $_POST['country'],
        'st' => $_POST['state'],
        'l'  => $_POST['locality'],
        'o'  => $_POST['organization'],
        'ou' => $_POST['org_unit'],
        'cn' => $_POST['common_name']
    ];

    // Recuperiamo la lunghezza della chiave dalla form (se manca, per la CA il default è 4096)
    $keyBits = isset($_POST['key_bits']) ? intval($_POST['key_bits']) : 4096;

    // Passiamo $keyBits come terzo argomento della funzione createCA
    if (SSLEngine::createCA($dnData, intval($_POST['days']), $keyBits)) {
        $msg = 'CA Generata con Successo!'; 
        $type = 'success';
    } else {
        $msg = 'Errore durante la generazione della CA.'; 
        $type = 'danger';
    }
}

$cas = $pdo->query("SELECT * FROM cas ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php require_once 'includes/head.php'; ?>
    <title>AegisCA | CA Manager</title>
</head>
<body>
    <?php include 'includes/topbar.php'; ?>
    
    <div class="container">
        <?php if($msg): ?><div class="alert alert-<?=$type?>"><?=$msg?></div><?php endif; ?>

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
                <button type="submit" name="create_ca" class="btn">Genera Root CA</button>
            </form>
        </div>

        <div class="panel">
            <h3>Certificate Authorities Certificate</h3>
            <table>
                <thead>
                    <tr>
                        <th>Common Name</th>
                        <th>Soggetto Completo</th>
                        <th>Creazione</th>
                        <th>Scadenza</th>
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
                        <td><small>/C=<?=$ca['subject_country']?>/ST=<?=$ca['subject_state']?>/L=<?=$ca['subject_locality']?>/O=<?=$ca['subject_organization']?>/OU=<?=$ca['subject_org_unit']?></small></td>
                        <td><?=$ca['created_at']?></td>
                        <td><?=$ca['valid_to']?></td>
                        <td>
                            <span class="badge <?=$isExpired ? 'badge-danger' : 'badge-success'?>">
                                <?=$isExpired ? 'Scaduta' : 'Attiva'?>
                            </span>
                        </td>
                        <td>
                            <a href="download.php?type=ca_cert&id=<?=$ca['id']?>" class="btn btn-sm">Esporta CRT</a>
                            <a href="download.php?type=ca_key&id=<?=$ca['id']?>" class="btn btn-sm" style="background-color:#64748b;">Esporta KEY</a>
                            <a href="manage_ca.php?delete=<?=$ca['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questa CA e invalidare i certificati emessi?')">Elimina</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
