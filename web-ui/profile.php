<?php
require_once 'auth.php';
Auth::check();

$msg = ''; $type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['new_password'])) {
        if (Auth::changePassword($_SESSION['user_id'], $_POST['new_password'])) {
            $msg = 'Password aggiornata con successo.'; $type = 'success';
        } else {
            $msg = 'Errore durante l\'aggiornamento della password.'; $type = 'danger';
        }
    } else {
        $msg = 'La password non può essere vuota.'; $type = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php require_once 'includes/head.php'; ?>
    <title>AegisCA | Profilo</title>
</head>
<body>
    <?php include 'includes/topbar.php'; ?>

    <div class="container" style="max-width: 500px;">
        <div class="panel">
            <h3>Cambia Password Amministratore</h3>
            <?php if($msg): ?><div class="alert alert-<?=$type?>"><?=$msg?></div><?php endif; ?>
            <form method="POST">
                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label>Nuova Password</label>
                    <input type="password" name="new_password" required minlength="8">
                </div>
                <button type="submit" class="btn" style="width:100%;">Aggiorna Credenziali</button>
            </form>
        </div>
    </div>
</body>
</html>
