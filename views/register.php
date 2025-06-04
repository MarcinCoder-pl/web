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
        <label>Login:<br>
            <input type="text" name="username" required>
        </label><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label>Hasło:<br>
            <input type="password" name="password" required>
        </label><br><br>

        <label>Powtórz hasło:<br>
            <input type="password" name="confirm_password" required>
        </label><br><br>

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="submit" value="Zarejestruj się">
    </form>
</div>

