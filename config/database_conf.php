<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}

// Dane dostępowe do bazy danych
$host = 'localhost';        // lub adres Twojego serwera
$dbname = 'loginy_DB';      // zmień na nazwę swojej bazy danych
$db_user = 'webuser';      // zmień na swojego użytkownika bazy
$password = 'strong_password';  // zmień na swoje hasło



define('MIN_LENGTH_REGISTER', 5);
define('MAX_LENGTH_REGISTER' ,32);
define('MAX_LOGIN_ATTEMPTS', 5);
define('ADD_ACC', 'INSERT INTO uzytkownicy (login, haslo) VALUES (?, ?)');



