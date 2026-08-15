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
        'signup'       => ['file' => 'src/Pages/signup.php',       'auth_required' => false, 'has_layout' => true],
        
        // Pagina di Errore 404
        '404'          => ['file' => 'src/Pages/404.php',          'auth_required' => false, 'has_layout' => true],
        
        // Azioni pure di backend (Niente HTML, fanno logica e redirect/download)
        'logout'       => ['file' => 'src/Actions/logout.php',     'auth_required' => true,  'has_layout' => false],
        'download'     => ['file' => 'src/Actions/download.php',   'auth_required' => true,  'has_layout' => false],
    ];

    public function handleRequest() {
        // 1. Estrazione dinamica della pagina
        $page = $_GET['page'] ?? ($_GET['action'] ?? null);

        // Se non passa da $_GET, ricava il percorso dall'URI richiesto (es. /die -> die)
        if (!$page) {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $slug = trim($path, '/');
            
            // Rimuove l'eventuale 'index.php' iniziale dall'URI
            $slug = preg_replace('/^index\.php\/?/', '', $slug);
            
            $page = !empty($slug) ? $slug : 'dashboard';
        }

        // 2. Controllo esistenza della rotta: fallback sulla pagina '404'
        if (!array_key_exists($page, $this->allowedPages)) {
            $page = '404';
        }

        $pageConfig = $this->allowedPages[$page];

        // 3. Controllo Autenticazione
        if ($pageConfig['auth_required'] && !Auth::isLoggedIn()) {
            header('Location: index.php?page=login');
            exit;
        }

        // Impediamo l'accesso a login e register se l'utente è già autenticato
        if (!$pageConfig['auth_required'] && Auth::isLoggedIn() && ($page === 'login' || $page === 'register')) {
            header('Location: index.php?page=dashboard');
            exit;
        }

        // 4. Esecuzione del file di Logica/Pagina
        if ($pageConfig['has_layout']) {
            ob_start();
            require_once ROOT_PATH . $pageConfig['file'];
            $pageContent = ob_get_clean(); // Cattura l'HTML generato

            // 5. Rendering del Layout completo
            require_once ROOT_PATH . 'templates/head.php';

            // Mostra la nav a qualsiasi utente loggato (anche su 404)
            if (Auth::isLoggedIn()) {
                require_once ROOT_PATH . 'templates/nav.php';
            }

            // Mostra il footer a qualsiasi utente loggato (anche su 404)
            if (Auth::isLoggedIn()) {
                require_once ROOT_PATH . 'templates/footer.php';
            }

            // Inietta il contenuto catturato prima
            echo $pageContent;
        } else {
            // Per azioni trasparenti di backend (download, logout)
            require_once ROOT_PATH . $pageConfig['file'];
        }
    }
}