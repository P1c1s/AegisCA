<?php
// src/Pages/dashboard.php

global $pdo;

// 1. Recuperiamo le metriche principali dal Database
try {
    // Conteggio totale delle Certificate Authorities
    $totalCas = (int)$pdo->query("SELECT COUNT(*) FROM cas")->fetchColumn();

    // Conteggio totale dei certificati SSL emessi
    $totalCerts = (int)$pdo->query("SELECT COUNT(*) FROM certificates")->fetchColumn();

    // Calcolo diretto in SQL dei certificati scaduti e attivi
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
        <h2><?= __('dashboard_welcome_title', 'Welcome to AegisCA Control Panel') ?></h2>
        <p style="color: var(--text-muted);"><?= __('dashboard_welcome_description', 'Centralized and secure management of your local Certificate Authorities and SSL certificates for your HomeLab.') ?></p>
    </div>

    <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 2rem;">
        
        <div class="panel" style="border-left: 4px solid var(--accent); padding: 20px;">
            <small style="color: var(--text-muted); text-transform: uppercase; font-weight: bold; font-size: 0.75rem;"><?= __('dashboard_stat_configured_cas', 'Configured Root CAs') ?></small>
            <h3 style="font-size: 2rem; margin: 10px 0 0 0;"><?= $totalCas ?></h3>
        </div>

        <div class="panel" style="border-left: 4px solid #0284c7; padding: 20px;">
            <small style="color: var(--text-muted); text-transform: uppercase; font-weight: bold; font-size: 0.75rem;"><?= __('dashboard_stat_issued_certs', 'Issued Certificates') ?></small>
            <h3 style="font-size: 2rem; margin: 10px 0 0 0;"><?= $totalCerts ?></h3>
        </div>

        <div class="panel" style="border-left: 4px solid #22c55e; padding: 20px;">
            <small style="color: var(--text-muted); text-transform: uppercase; font-weight: bold; font-size: 0.75rem;"><?= __('dashboard_stat_active_certs', 'Active Certificates') ?></small>
            <h3 style="font-size: 2rem; margin: 10px 0 0 0; color: #22c55e;"><?= $activeCerts ?></h3>
        </div>

        <div class="panel" style="border-left: 4px solid #ef4444; padding: 20px;">
            <small style="color: var(--text-muted); text-transform: uppercase; font-weight: bold; font-size: 0.75rem;"><?= __('dashboard_stat_expired_certs', 'Expired Certificates') ?></small>
            <h3 style="font-size: 2rem; margin: 10px 0 0 0; color: #ef4444;"><?= $expiredCerts ?></h3>
        </div>

    </div>

    <div class="panel">
        <h3><?= __('dashboard_quick_actions_title', 'Quick Actions') ?></h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;"><?= __('dashboard_quick_actions_desc', 'Select an operation to start managing your Public Key Infrastructure (PKI).') ?></p>
        
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="index.php?page=manage_ca" class="btn" style="text-decoration: none; display: inline-block; text-align: center;">
                <?= __('dashboard_btn_manage_cas', 'Manage Authorities (CA)') ?>
            </a>
            <a href="index.php?page=manage_certs" class="btn" style="background-color: #0284c7; text-decoration: none; display: inline-block; text-align: center;">
                <?= __('dashboard_btn_issue_cert', 'Issue SSL Certificate') ?>
            </a>
            <a href="index.php?page=import" class="btn" style="background-color: #475569; text-decoration: none; display: inline-block; text-align: center;">
                <?= __('dashboard_btn_import_files', 'Import Existing Files') ?>
            </a>
        </div>
    </div>
</div>