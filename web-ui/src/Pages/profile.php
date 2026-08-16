<?php
// src/Pages/profile.php

$msg = ''; 
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verifica CSRF
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $msg = __('profile_error_csrf_invalid', 'Invalid request or session expired.');
        $type = 'danger';
    } else {
        $userId = $_SESSION['user_id'];
        $newLang = $_POST['default_lang'] ?? 'it';
        $newPassword = trim($_POST['new_password'] ?? '');

        $langUpdated = false;
        $passwordUpdated = false;
        $hasError = false;

        // 2. Aggiornamento Lingua (solo se diversa da quella attuale)
        $current_lang = $_SESSION['lang'] ?? 'en';
        if ($newLang !== $current_lang) {
            if (Auth::updateLanguage($userId, $newLang)) {
                $_SESSION['lang'] = $newLang; // Aggiorna subito la sessione
                $langUpdated = true;
            } else {
                $msg = __('profile_msg_error_lang', 'Error updating ' . $newLang . ' language.');
                $type = 'danger';
                $hasError = true;
            }
        }

        // 3. Aggiornamento Password (solo se inserita e se non ci sono stati errori precedenti)
        if (!$hasError && !empty($newPassword)) {
            if (Auth::changePassword($userId, $newPassword)) {
                $passwordUpdated = true;
            } else {
                $msg = __('profile_msg_error_pwd', 'Error updating password.'); 
                $type = 'danger';
                $hasError = true;
            }
        }

        // 4. Gestione Messaggio di Successo / Nessuna Modifica
        if (!$hasError) {
            if ($langUpdated || $passwordUpdated) {
                // Recupera il messaggio tradotto o usa il testo di default
                $translatedMsg = __('profile_msg_success', 'Profile updated successfully.');
                $msg = !empty($translatedMsg) ? $translatedMsg : 'Profile updated successfully.';
                $type = 'success';
            } else {
                $translatedMsg = __('profile_msg_no_changes', 'No changes were made.');
                $msg = !empty($translatedMsg) ? $translatedMsg : 'No changes were made.';
                $type = 'info';
            }
        }
    }
}

// Recupera la lingua aggiornata per il menu a tendina
$current_lang = $_SESSION['lang'] ?? 'en';
?>

<div class="container" style="max-width: 500px;">
    <div class="panel">
        <h3><?= __('profile_title', 'User Settings') ?></h3>
        
        <?php if (!empty($msg)): ?>
            <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Campo Token CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(getCsrfToken()) ?>">

            <!-- Selezione Lingua -->
            <div class="form-group" style="margin-bottom:1.2rem;">
                <label><?= __('profile_label_language', 'Interface Language') ?></label>
                <select name="default_lang" required>
                    <option value="de" <?= $current_lang === 'de' ? 'selected' : '' ?>>🇩🇪 Deutsch (DE)</option>
                    <option value="en" <?= $current_lang === 'en' ? 'selected' : '' ?>>English (EN)</option>
                    <option value="fi" <?= $current_lang === 'fi' ? 'selected' : '' ?>>Suomi / Finnish (FI)</option>
                    <option value="fr" <?= $current_lang === 'fr' ? 'selected' : '' ?>>Français (FR)</option>
                    <option value="it" <?= $current_lang === 'it' ? 'selected' : '' ?>>Italiano (IT)</option>
                    <option value="pl" <?= $current_lang === 'pl' ? 'selected' : '' ?>>Polski (PL)</option>
                    <option value="ro" <?= $current_lang === 'ro' ? 'selected' : '' ?>>Română (RO)</option>
                </select>
            </div>

            <!-- Nuova Password -->
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label><?= __('profile_label_new_password', 'New Password (leave blank to keep current)') ?></label>
                <input type="password" name="new_password" placeholder="<?= __('profile_ph_new_password', 'Enter new password') ?>" minlength="8">
            </div>

            <button type="submit" class="btn" style="width:100%;"><?= __('profile_btn_submit', 'Save Changes') ?></button>
        </form>
    </div>
</div>