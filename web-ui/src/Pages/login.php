<?php
// src/Pages/login.php
// Nota: Auth, $pdo, e head.php sono già stati caricati dal PageController.

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Raccogliamo e sanifichiamo velocemente i dati di input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (Auth::login($username, $password)) {
        // Login riuscito: reindirizziamo alla dashboard tramite il Front Controller
        header('Location: index.php?page=dashboard');
        exit;
    } else {
        $error = 'Credenziali non valide.';
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
            <a href="index.php?page=register" style="color:var(--accent); text-decoration:none;">Registra un nuovo Admin</a>
        </p>
    </div>
</div>