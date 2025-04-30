 <?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
	
?>
 <div class="container">
		<h2>Register</h2>
		<form method="POST" action="../tools/authenticate.php">
			<!-- Ukryte pole okreslajace akcje -->
			<input type="hidden" name="action" value="login">
			
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" name="username" id="username" required><br>
			
			<label for="password">Hasło:</label>
            <input type="password" name="password" id="password" required><br>
			
			<label for="password_confirm">Potwierdź hasło:</label>
            <input type="password" name="password_confirm" id="password_confirm" required><br>
	
            <input type="submit" value="login">	
		</form>
</div>
