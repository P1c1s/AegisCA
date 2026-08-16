<?php
// src/Pages/signup.php
// Nota: Auth, $pdo e il layout sono gestiti centralmente dal PageController.

$msg = ''; 
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica CSRF
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $msg = __('signup_error_csrf_invalid', 'Invalid request or session expired.');
        $type = 'danger';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!empty($username) && !empty($password)) {
            if (Auth::signup($username, $password)) {
                $msg = __('signup_msg_success', 'Registration completed. You can now log in.'); 
                $type = 'success';
            } else {
                $msg = __('signup_msg_error_exists', 'Registration error (Username already exists).'); 
                $type = 'danger';
            }
        } else {
            $msg = __('signup_msg_required_fields', 'All fields are required.');
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

        <h2 style="text-align:center; font-size:1.3rem; margin-bottom:1.5rem;"><?= __('signup_title', 'Sign up to AegisCA') ?></h2>
        
        <?php if ($msg): ?>
            <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Campo Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

            <div class="form-group" style="margin-bottom:1rem;">
                <label><?= __('signup_label_username', 'Username') ?></label>
                <input type="text" name="username" placeholder="<?= __('signup_ph_username', 'Enter username') ?>" required>
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label><?= __('signup_label_password', 'Password') ?></label>
                <input type="password" name="password" placeholder="<?= __('signup_ph_password', 'Enter password') ?>" required>
            </div>
            <button type="submit" class="btn" style="width:100%;"><?= __('signup_btn_submit', 'Sign Up') ?></button>
        </form>
        
        <p style="margin-top:1rem; font-size:0.85rem; text-align:center;">
            <a href="index.php?page=login" style="color:var(--accent); text-decoration:none;"><?= __('signup_back_to_login', 'Back to Login') ?></a>
        </p>
    </div>
</div>