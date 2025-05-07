<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
	
// Lista dozwolonych stron
$allowed_pages = ['home', 'contact', 'register', 'login', 'dashboard', 'shop', 'event', 'download', 'profile'];

// Jeśli zmienna $page jest ustawiona i strona jest w dozwolonych stronach
if (isset($page) && in_array($page, $allowed_pages)) {
    require_once "views/{$page}.php";  // Załadowanie odpowiedniej strony
} else {
    // Jeśli strona nie istnieje, ładujemy stronę 404
    require_once "views/404.php";
}
?>
