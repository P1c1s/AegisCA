<?php
require_once 'auth.php';
$msg = ''; $type = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (Auth::register($_POST['username'], $_POST['password'])) {
        $msg = 'Registrazione completata. Puoi effettuare il login.'; $type = 'success';
    } else {
        $msg = 'Errore durante la registrazione (Username già esistente).'; $type = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php require_once 'includes/head.php'; ?>
    <title>AegisCA | Registrazione</title>
</head>
<body class="login-body">
    <div class="panel" style="width:100%; max-width:400px; margin-bottom:0;">
        
        <div class="login-brand">
            <div class="logo">
                <img src="assets/img/aegis-ca.svg" alt="AegisCA Logo" class="logo-img">
            </div>
        </div>

        <h2 style="text-align:center; font-size:1.3rem; margin-bottom:1.5rem;">Registrati a AegisCA</h2>
        
        <?php if($msg): ?>
            <div class="alert alert-<?=$type?>"><?=$msg?></div>
        <?php endif; ?>
        
        <form method="POST">
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
            <a href="login.php" style="color:var(--accent); text-decoration:none;">Torna al Login</a>
        </p>
    </div>
</body>
</html>