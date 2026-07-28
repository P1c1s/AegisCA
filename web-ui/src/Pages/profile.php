<?php
// src/Pages/profile.php
// Nota: Auth, $pdo e il layout sono gestiti centralmente dal PageController.

$msg = ''; 
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica CSRF
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $msg = 'Richiesta non valida o token CSRF scaduto.';
        $type = 'danger';
    } else {
        $newPassword = $_POST['new_password'] ?? '';

        if (!empty(trim($newPassword))) {
            if (Auth::changePassword($_SESSION['user_id'], $newPassword)) {
                $msg = 'Password aggiornata con successo.'; 
                $type = 'success';
            } else {
                $msg = 'Errore durante l\'aggiornamento della password.'; 
                $type = 'danger';
            }
        } else {
            $msg = 'La password non può essere vuota.'; 
            $type = 'danger';
        }
    }
}
?>

<div class="container" style="max-width: 500px;">
    <div class="panel">
        <h3>Cambia Password Amministratore</h3>
        
        <?php if ($msg): ?>
            <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Campo Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

            <div class="form-group" style="margin-bottom:1.5rem;">
                <label>Nuova Password</label>
                <input type="password" name="new_password" required minlength="8">
            </div>
            <button type="submit" class="btn" style="width:100%;">Aggiorna Credenziali</button>
        </form>
    </div>
</div>