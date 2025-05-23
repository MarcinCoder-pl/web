<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}

session_start(); // dodaj, jeśli nie masz włączonych sesji

require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/sql_queries.php';

$db = new Database($db_host, $db_user, $db_password, $db_name);
$language = $_SESSION['language'] ?? 'pl'; // domyślny język
