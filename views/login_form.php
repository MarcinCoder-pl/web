<?php
if (!defined('ACCESS')) {
    die('Brak dostępu do formularza logowania.');
}
require_once __DIR__ . '/../tools/csrf_token.php';
?>
<div class="container">
    <h2>Logowanie</h2>
    <form method="post" action="../tools/authenticate_login.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

        <label for="username">Login:</label>
        <input type="text" name="username" required>

        <label for="password">Hasło:</label>
        <input type="password" name="password" required autocomplete="off">

        <button type="submit" name="login" value="loguj">Zaloguj się</button>
    </form>
</div>
