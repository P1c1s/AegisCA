<?php
// templates/head.php
// $pageTitle viene definita centralmente dal PageController.
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'en') ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Aegis CA - HomeLab PKI Manager" />
    <meta name="author" content="Lorenzo Ricciardi" />
    <meta name="keywords" content="Aegis CA, PKI, SSL, Certificates" />
    <meta name="robots" content="index, follow" />

    <!-- 1. Per Safari/DuckDuckGo Tab su iOS (Avanza la versione HD 192x192 per evitare l'effetto sgranato) -->
    <link rel="icon" type="image/png" sizes="192x192" href="assets/img/android-chrome-192x192.png" />
    <!-- 2. Per iOS Schermata Home / Preferiti -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png" />
    <!-- 3. Per Browser Desktop Moderni (Vettoriale) -->
    <link rel="icon" type="image/svg+xml" href="assets/img/aegis-ca.svg" />
    <!-- 4. Manifest Android/PWA -->
    <link rel="manifest" href="assets/img/site.webmanifest" />

    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <title><?= htmlspecialchars($pageTitle ?? 'AegisCA') ?></title>
</head>
<body>