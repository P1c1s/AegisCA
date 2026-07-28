<?php
// src/Pages/register.php
// Nota: Auth, $pdo e il layout sono gestiti centralmente dal PageController.

$msg = ''; 
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica CSRF
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $msg = 'Richiesta non valida o token CSRF scaduto.';
        $type = 'danger';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!empty($username) && !empty($password)) {
            if (Auth::register($username, $password)) {
                $msg = 'Registrazione completata. Puoi effettuare il login.'; 
                $type = 'success';
            } else {
                $msg = 'Errore durante la registrazione (Username già esistente).'; 
                $type = 'danger';
            }
        } else {
            $msg = 'Tutti i campi sono obbligatori.';
            $type = 'danger';
        }
    }
}
?>

<div class="login-body-wrapper" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="panel" style="width:100%; max-width:400px; margin-bottom:0;">
        
        <div class="login-brand">
            <div class="logo">
                <img src="assets/img/aegis-ca.svg" alt="AegisCA Logo" class="logo-img">
            </div>
        </div>

        <h2 style="text-align:center; font-size:1.3rem; margin-bottom:1.5rem;">Registrati a AegisCA</h2>
        
        <?php if ($msg): ?>
            <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Campo Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

            <div class="form-group" style="margin-bottom:1rem;">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn" style="width:100%;">Registra</button>
        </form>
        
        <p style="margin-top:1rem; font-size:0.85rem; text-align:center;">
            <a href="index.php?page=login" style="color:var(--accent); text-decoration:none;">Torna al Login</a>
        </p>
    </div>
</div>