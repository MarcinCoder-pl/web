<?php
use Tools_Manager\SessionManager;

if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
}

$session = SessionManager::getInstance();
$csrfToken = $session->getOrCreateCsrfToken('login');
?>

<h2><?= $languageManager->t('login_page') ?></h2>

<?php if ($session->has('login_error')): ?>
    <div style="color: red;">
        <?php 
        $errors = $session->get('login_error');

        if (is_array($errors)) {
            foreach ($errors as $field => $messages) {
                foreach ($messages as $msg) {
                    echo '<p>' 
                        . htmlspecialchars($languageManager->t($field) ?? $field)
                        . ': '
                        . htmlspecialchars($msg)
                        . '</p>';
                }
            }
        } else {
            echo htmlspecialchars($errors);
        }
        ?>
    </div>
    <?php $session->delete('login_error'); ?>
<?php endif; ?>

<div class="register-container">
    <form action="tools/user_core/user_login.php" method="post">

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <label for="username">
            <?= $languageManager->t('login_user_page') ?>
        </label>
        <input type="text" id="username" name="username" required>

        <br>

        <label for="password">
            <?= $languageManager->t('login_password_page') ?>
        </label>
        <input type="password" id="password" name="password" required>

        <br>

        <input type="submit" value="<?= $languageManager->t('login_button_page') ?>">
    </form>
</div>
