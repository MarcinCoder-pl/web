<?php
if (!defined('ACCESS')) {
    die('Brak dostępu do formularza rejestracji.');
}
require_once __DIR__ . '/../tools/csrf_token.php';
?>
<div class="post-container">
    <h2>Rejestracja</h2>
		<form method="post" action="/../tools/register_user.php">
        
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken(); ?>">

	<div>
		<label for="username">Login:</label><br>
		<input type="text" name="username" required>
	</div>			
	
	<div>			
		<label for="password">Hasło:</label><br>
		<input type="password" name="password" required autocomplete="off">	
	<div>

	<div>
		<label for="password_confirm">Potwierdź hasło:</label><br>
        <input type="password" name="password_confirm" required>	
	</div>
	<div>
    <label for="email">Email:</label><br>
    <input type="email" name="email" required>
</div>



        <br><button type="submit" name="register" value="rejestruj">Zarejestruj się</button>
    </form>
    <?php if (isset($_SESSION['error'])): ?>
    <div class="error-message"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>

</div>
