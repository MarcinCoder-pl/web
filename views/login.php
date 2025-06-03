<?php
use Tools_Manager\SessionManager;
if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
}

$session = SessionManager::getInstance();
$csrfToken = $session->getOrCreateCsrfToken('login');
?>
 <h2>Logowanie</h2>

    <?php if ($session->has('login_error')): ?>
        <div style="color: red;"><?php echo htmlspecialchars($session->get('login_error')); ?></div>
        <?php $session->delete('login_error'); ?>
    <?php endif; ?>

    <form action="tools/user_core/user_login.php" method="post">

        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required>

        <br>

        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required>

        <br>

        <button type="submit">Zaloguj się</button>
    </form>
