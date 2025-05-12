<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
	require_once __DIR__ . '/../tools/csrf_token.php';
	
if (!isset($_SESSION['username'])) {
    // Jeśli nie, przekierowanie na stronę logowania
    header('Location: home.php');
    exit;
}	
?>
<BR>dashboard

<!-- Formularz wylogowywania z tokenem CSRF -->
<form method="post" action="../tools/logout.php">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>" >
    <button type="submit" name="logout" value="logout">Wyloguj się</button>
</form>
