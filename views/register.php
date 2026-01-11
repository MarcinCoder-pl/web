<?php
use Tools_Manager\SessionManager;

$session = SessionManager::getInstance();
$csrfToken = $session->getOrCreateCsrfToken('register');
?>


<div class="register-container">
    <h2>Formularz rejestracyjny</h2>

    <?php if ($session->has('register_error')): ?>
        <div class="error-message"><?php echo $session->get('register_error'); $session->delete('register_error'); ?></div>
    <?php endif; ?>

    <form action="tools/user_core/user_register.php" method="post">
        <label>
            <?= $languageManager->t('username') ?>
        <br>
            <input type="text" name="username" required>
        </label><br><br>

        <label for="email"><?= $languageManager->t('email') ?></label>
        <input type="email" name="email" id="email" required><br><br>

        <label><?= $languageManager->t('password') ?> <br>
            <input type="password" name="password" required>
        </label><br><br>

        <label><?= $languageManager->t('confirm_password') ?><br>
            <input type="password" name="confirm_password" required>
        </label><br><br>

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="submit" value="<?= $languageManager->t('register_button') ?>">
    </form>
</div>

