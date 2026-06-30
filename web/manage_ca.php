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
    if (SSLEngine::createCA($dnData, intval($_POST['days']))) {
        $msg = 'CA Generata con Successo!'; $type = 'success';
    } else {
        $msg = 'Errore durante la generazione della CA.'; $type = 'danger';
    }
}

$cas = $pdo->query("SELECT * FROM cas ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8"><title>Gestione CA - SSL Manager</title>
    <style><?php include 'style.css'; ?></style>
</head>
<body>
    <?php include 'topbar.php'; ?>
    <div class="container">
        <?php if($msg): ?><div class="alert alert-<?=$type?>"><?=$msg?></div><?php endif; ?>

        <div class="panel">
            <h3>Crea Nuova Local Certificate Authority (Root CA)</h3>
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Country (C) [Max 2 lettere]</label>
                        <input type="text" name="country" placeholder="IT" maxlength="2" required>
                    </div>
                    <div class="form-group">
                        <label>State/Province (ST)</label>
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
