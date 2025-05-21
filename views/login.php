<?php
if (!defined('ACCESS')) {
    die('Brak dostępu do formularza logowania.');
}
if(isset($_SESSION['username']) )
{
	header('Location: home.php');
}
require_once __DIR__ . '/../tools/csrf_token.php';
?>
<div class="post-container">
    <h2>Logowanie</h2>
    <form method="post" action="../tools/login_user.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

        <label for="username">Login:</label>
        <input type="text" name="username" required>

        <label for="password">Hasło:</label>
        <input type="password" name="password" required autocomplete="off">

        <button type="submit" name="login" value="loguj">Zaloguj się</button>
    </form>
        <?php if (isset($_SESSION['error'])): ?>
    <div class="error-message"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>
</div>
