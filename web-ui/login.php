<?php
require_once 'auth.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (Auth::login($_POST['username'], $_POST['password'])) {
        header('Location: index.php');
        exit;
    } else {
        $error = 'Credenziali non valide.';
    }
}

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <?php require_once 'includes/head.php'; ?>
    <title>AegisCA | Login</title>
</head>

<body class="login-body">
    <div class="panel" style="width:100%; max-width:400px; margin-bottom:0;">
        
        <div class="login-brand">
            <div class="logo">
                <img src="assets/img/aegis-ca.svg" alt="AegisCA Logo" class="logo-img">
            </div>
        </div>

        <h2 style="text-align:center; font-size:1.3rem; margin-bottom:1.5rem;">Accedi a AegisCA</h2>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?=$error?></div>
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
            <button type="submit" class="btn" style="width:100%;">Accedi</button>
        </form>
        
        <p style="margin-top:1rem; font-size:0.85rem; text-align:center;">
            <a href="register.php" style="color:var(--accent); text-decoration:none;">Registra un nuovo Admin</a>
        </p>
    </div>
</body>
</html>