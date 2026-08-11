<?php
// src/Pages/404.php
// Nota: Gestito centralmente dal PageController.
// È buona norma impostare il codice di risposta HTTP 404
http_response_code(404);
?>

<div class="not-found-body-wrapper" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="panel" style="width:100%; max-width:480px; margin-bottom:0; text-align: center;">
        
        <div class="login-brand" style="margin-bottom: 1rem;">
            <div class="logo">
                <img src="assets/img/aegis-ca.svg" alt="AegisCA Logo" class="logo-img" style="max-height: 60px;">
            </div>
        </div>

        <h1 style="font-size: 4rem; margin: 0; color: #e74c3c; line-height: 1;">404</h1>
        <h2 style="font-size: 1.3rem; margin-top: 0.5rem; margin-bottom: 1rem;">Pagina non trovata</h2>
        
        <p style="color: #666; margin-bottom: 2rem;">
            La pagina che stai cercando non esiste, è stata rimossa o l'URL inserito non è corretto.
        </p>

        <div style="display: flex; gap: 10px; justify-content: center;">
            <a href="index.php?page=dashboard" class="btn" style="text-decoration: none;">
                Torna alla Dashboard
            </a>
        </div>
    </div>
</div>