<?php
if (!defined('ACCESS')) {
    die('Brak dostępu do formularza rejestracji.');
}
require_once __DIR__ . '/../tools/csrf_token.php';
?>
<div class="container">
    <h2>Rejestracja</h2>
		<form method="post" action="/../tools/register_user.php">

        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">

        <label for="username">Login:</label>
        <input type="text" name="username" required>

        <label for="password">Hasło:</label>
        <input type="password" name="password" required autocomplete="off">


        <label for="password_confirm">Potwierdź hasło:</label>
        <input type="password" name="password_confirm" required>

        <button type="submit" name="register" value="rejestruj">Zarejestruj się</button>
    </form>
</div>
