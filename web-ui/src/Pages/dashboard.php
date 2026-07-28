<?php
// src/Pages/dashboard.php
// Nota: Auth, $pdo e il layout sono gestiti centralmente dal PageController.

global $pdo;

// 1. Recuperiamo le metriche principali dal Database
try {
    // Conteggio totale delle Certificate Authorities
    $totalCas = (int)$pdo->query("SELECT COUNT(*) FROM cas")->fetchColumn();

    // Conteggio totale dei certificati SSL emessi
    $totalCerts = (int)$pdo->query("SELECT COUNT(*) FROM certificates")->fetchColumn();

    // Calcolo diretto in SQL dei certificati scaduti e attivi (molto più efficiente e sicuro)
    $stats = $pdo->query("
        SELECT 
            SUM(CASE WHEN valid_to < NOW() THEN 1 ELSE 0 END) AS expired_certs,
            SUM(CASE WHEN valid_to >= NOW() THEN 1 ELSE 0 END) AS active_certs
        FROM certificates
    ")->fetch();

    $expiredCerts = (int)($stats['expired_certs'] ?? 0);
    $activeCerts  = (int)($stats['active_certs'] ?? 0);

} catch (PDOException $e) {
    $totalCas = 0;
    $totalCerts = 0;
    $expiredCerts = 0;
    $activeCerts = 0;
}
?>

<div class="container">
    <div class="welcome-block" style="margin-bottom: 2rem;">
        <h2>Benvenuto nel Pannello di Controllo AegisCA</h2>
        <p style="color: var(--text-muted);">Gestione centralizzata e sicura delle tue Certificate Authorities locali e dei certificati SSL per il tuo HomeLab.</p>
    </div>

    <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 2rem;">
        
        <div class="panel" style="border-left: 4px solid var(--accent); padding: 20px;">
            <small style="color: var(--text-muted); text-transform: uppercase; font-weight: bold; font-size: 0.75rem;">Root CA Configurate</small>
            <h3 style="font-size: 2rem; margin: 10px 0 0 0;"><?= $totalCas ?></h3>
        </div>

        <div class="panel" style="border-left: 4px solid #0284c7; padding: 20px;">
            <small style="color: var(--text-muted); text-transform: uppercase; font-weight: bold; font-size: 0.75rem;">Certificati Emessi</small>
            <h3 style="font-size: 2rem; margin: 10px 0 0 0;"><?= $totalCerts ?></h3>
        </div>

        <div class="panel" style="border-left: 4px solid #22c55e; padding: 20px;">
            <small style="color: var(--text-muted); text-transform: uppercase; font-weight: bold; font-size: 0.75rem;">Certificati Attivi</small>
            <h3 style="font-size: 2rem; margin: 10px 0 0 0; color: #22c55e;"><?= $activeCerts ?></h3>
        </div>

        <div class="panel" style="border-left: 4px solid #ef4444; padding: 20px;">
            <small style="color: var(--text-muted); text-transform: uppercase; font-weight: bold; font-size: 0.75rem;">Certificati Scaduti</small>
            <h3 style="font-size: 2rem; margin: 10px 0 0 0; color: #ef4444;"><?= $expiredCerts ?></h3>
        </div>

    </div>

    <div class="panel">
        <h3>Azioni Rapide</h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Seleziona un'operazione per iniziare a gestire la tua infrastruttura a chiave pubblica (PKI).</p>
        
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="index.php?page=manage_ca" class="btn" style="text-decoration: none; display: inline-block; text-align: center;">
                Gestisci Autorità (CA)
            </a>
            <a href="index.php?page=manage_certs" class="btn" style="background-color: #0284c7; text-decoration: none; display: inline-block; text-align: center;">
                Emetti Certificato SSL
            </a>
            <a href="index.php?page=import" class="btn" style="background-color: #475569; text-decoration: none; display: inline-block; text-align: center;">
                Importa File Esistenti
            </a>
        </div>
    </div>
</div>