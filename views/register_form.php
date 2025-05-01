<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sprawdzamy, czy stała ACCESS jest zdefiniowana
if (!defined('ACCESS')) {
    die('Brak dostępu do formularza rejestracji.');
}

require_once __DIR__ . '/../tools/csrf_token.php';

?>
<div class="container">
    <h2>Rejestracja</h2>
<form method="post" action="../tools/authenticate.php">
    
     <?php echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCsrfToken()) . '">'; ?>
    
    <label for="username">Login:</label>
    <input type="text" name="username" required>

    <label for="password">Hasło:</label>
    <input type="password" name="password" required>

    <label for="password_confirm">Potwierdź hasło:</label>
    <input type="password" name="password_confirm" required>

    <button type="submit">Zarejestruj się</button>
</form>
</div>

