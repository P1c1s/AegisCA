<?php
// src/Pages/login.php
// Nota: Auth, $pdo e il layout sono gestiti centralmente dal PageController.

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica CSRF usando l'helper del config
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = __('login_error_csrf_invalid', 'Invalid request or session expired.');
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (Auth::login($username, $password)) {
            // Rigenera il token dopo il login
            unset($_SESSION['csrf_token']);
            header('Location: index.php?page=dashboard');
            exit;
        } else {
            $error = __('login_error_invalid_credentials', 'Invalid username or password.');
        }
    }
}
?>

<div class="login-body-wrapper" style="display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="panel" style="width:100%; max-width:400px; margin-bottom:0;">
        <div class="login-brand">
            <div class="logo">
                <img src="assets/img/aegis-ca.svg" alt="AegisCA Logo" class="logo-img">
            </div>
        </div>
        <h2 style="text-align:center; font-size:1.3rem; margin-bottom:1.5rem;"><?= __('login_heading', 'Login to AegisCA') ?></h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Helper Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

            <div class="form-group" style="margin-bottom:1rem;">
                <label><?= __('login_label_username', 'Username') ?></label>
                <input type="text" name="username" placeholder="<?= __('login_ph_username', 'Enter username') ?>" required>
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label><?= __('login_label_password', 'Password') ?></label>
                <input type="password" name="password" placeholder="<?= __('login_ph_password', 'Enter password') ?>" required>
            </div>
            <button type="submit" class="btn" style="width:100%;"><?= __('login_btn', 'Login') ?></button>
        </form>

        <p style="margin-top:1rem; font-size:0.85rem; text-align:center;">
            <a href="index.php?page=signup" style="color:var(--accent); text-decoration:none;"><?= __('login_to_signup', 'Don\'t have an account? Sign up') ?></a>
        </p>
    </div>
</div>