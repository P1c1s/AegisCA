<?php
// src/Pages/login.php
// Nota: Auth, $pdo e il layout sono gestiti centralmente dal PageController.

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica CSRF usando l'helper del config
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Richiesta non valida o sessione scaduta.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (Auth::login($username, $password)) {
            // Rigenera il token dopo il login
            unset($_SESSION['csrf_token']);
            header('Location: index.php?page=dashboard');
            exit;
        } else {
            $error = 'Credenziali non valide.';
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
        <h2 style="text-align:center; font-size:1.3rem; margin-bottom:1.5rem;">Accedi a AegisCA</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Basta richiamare la funzione helper -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

            <div class="form-group" style="margin-bottom:1rem;">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn" style="width:100%;">Accedi</button>
        </form>
    </div>
</div>