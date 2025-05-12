<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}

// Dane dostępowe do bazy danych
$db_host = 'localhost';        // Adres serwera MySQL
$db_name = 'db_user_web';      // Nazwa Twojej bazy danych
$db_user = 'webuser';          // Użytkownik bazy danych
$db_password = 'haslo123';  // zmień na swoje hasło


define('MIN_LENGTH_REGISTER', 5);
define('MAX_LENGTH_REGISTER' ,32);
define('MAX_LOGIN_ATTEMPTS', 5);
define('ADD_ACC', 'INSERT INTO uzytkownicy (login, haslo) VALUES (?, ?)');
define('LOGIN_ACC','SELECT haslo FROM uzytkownicy WHERE login = ?');


