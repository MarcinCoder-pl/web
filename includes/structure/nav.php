<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
?>
<!-- structure/nav.php -->
<nav>
    <ul>
        <li><a href="index.php?strona=home">Home</a></li>
        <li><a href="index.php?strona=register_form">Register</a></li>
        <li><a href="index.php?strona=login_form">Login</a></li>
        <!-- Możesz dodać więcej linków do innych stron -->
    </ul>
</nav>
