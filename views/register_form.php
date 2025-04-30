<?php
// Sprawdzamy, czy stała ACCESS jest zdefiniowana
if (!defined('ACCESS')) {
    die('Brak dostępu do formularza rejestracji.');
}
?>
<div class="container">
    <h2>Rejestracja</h2>
<form method="post" action="../tools/authenticate.php">
    
    
    <label for="username">Login:</label>
    <input type="text" name="username" required>

    <label for="password">Hasło:</label>
    <input type="password" name="password" required>

    <label for="password_confirm">Potwierdź hasło:</label>
    <input type="password" name="password_confirm" required>

    <button type="submit">Zarejestruj się</button>
</form>
</div>

