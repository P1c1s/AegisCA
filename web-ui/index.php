<?php
require_once 'auth.php';
Auth::check();
global $pdo;

$countCA = $pdo->query("SELECT COUNT(*) FROM cas")->fetchColumn();
$countCerts = $pdo->query("SELECT COUNT(*) FROM certificates")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php require_once 'includes/head.php'; ?>
    <title>AegisCA | Dashboard</title>
</head>

<body>
    <?php include 'includes/topbar.php'; ?>
    <div class="container">
        <div class="panel">
            <h2>Benvenuto su AegisCA, <?=htmlspecialchars($_SESSION['username'])?></h2>
            <p style="color: var(--text-muted);">Pannello di controllo centralizzato per l'emissione e il tracciamento delle infrastrutture a chiave pubblica (PKI) del tuo HomeLab.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="panel" style="text-align: center;">
                <h3 style="color: var(--text-muted);">Certificate Authorities Attive</h3>
                <p style="font-size: 3rem; font-weight: bold; color: var(--accent);"><?=$countCA?></p>
                <a href="manage_ca.php" class="btn" style="margin-top:1rem;">Gestisci CA</a>
            </div>
            <div class="panel" style="text-align: center;">
                <h3 style="color: var(--text-muted);">Certificati SSL Emessi</h3>
                <p style="font-size: 3rem; font-weight: bold; color: var(--success);"><?=$countCerts?></p>
                <a href="manage_certs.php" class="btn" style="margin-top:1rem;">Gestisci Certificati</a>
            </div>
        </div>
    </div>
</body>
</html>


