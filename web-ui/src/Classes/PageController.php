<?php
// src/Classes/PageController.php

class PageController {
    private $allowedPages = [
        // Pagine standard con interfaccia grafica (HTML)
        'dashboard'    => ['file' => 'src/Pages/dashboard.php',    'auth_required' => true,  'has_layout' => true],
        'manage_ca'    => ['file' => 'src/Pages/manage_ca.php',    'auth_required' => true,  'has_layout' => true],
        'manage_certs' => ['file' => 'src/Pages/manage_certs.php', 'auth_required' => true,  'has_layout' => true],
        'profile'      => ['file' => 'src/Pages/profile.php',      'auth_required' => true,  'has_layout' => true],
        'import'       => ['file' => 'src/Pages/import.php',       'auth_required' => true,  'has_layout' => true],
        'login'        => ['file' => 'src/Pages/login.php',        'auth_required' => false, 'has_layout' => true],
        'register'     => ['file' => 'src/Pages/register.php',     'auth_required' => false, 'has_layout' => true],
        
        // Azioni pure di backend (Niente HTML, fanno logica e redirect/download)
        'logout'       => ['file' => 'src/Actions/logout.php',     'auth_required' => true,  'has_layout' => false],
        'download'     => ['file' => 'src/Actions/download.php',   'auth_required' => true,  'has_layout' => false],
    ];

    public function handleRequest() {
        // Gestione degli endpoint sia tramite ?page= che tramite ?action=
        $page = $_GET['page'] ?? ($_GET['action'] ?? 'dashboard');

        // 1. Controllo esistenza della rotta
        if (!array_key_exists($page, $this->allowedPages)) {
            http_response_code(404);
            die("Pagina o azione non trovata.");
        }

        $pageConfig = $this->allowedPages[$page];

        // 2. Controllo Autenticazione
        if ($pageConfig['auth_required'] && !Auth::isLoggedIn()) {
            header('Location: index.php?page=login');
            exit;
        }

        // Impediamo l'accesso a login e register se già loggati
        if (!$pageConfig['auth_required'] && Auth::isLoggedIn() && ($page === 'login' || $page === 'register')) {
            header('Location: index.php?page=dashboard');
            exit;
        }

        // 3. Esecuzione del file di Logica/Pagina
        // Usiamo l'output buffering se la pagina richiede un layout, così i file possono fare redirect liberamente
        if ($pageConfig['has_layout']) {
            ob_start();
            require_once ROOT_PATH . $pageConfig['file'];
            $pageContent = ob_get_clean(); // Cattura l'HTML della pagina senza inviarlo al browser

            // 4. Rendering del Layout completo nell'ordine corretto
            require_once ROOT_PATH . 'templates/head.php';

            if ($pageConfig['auth_required']) {
                require_once ROOT_PATH . 'templates/topbar.php';
            }

            // Sputa fuori l'HTML della pagina catturato prima
            echo $pageContent;
        } else {
            // Se è un'azione pura di backend (es. download o logout), la eseguiamo direttamente
            require_once ROOT_PATH . $pageConfig['file'];
        }
    }
}