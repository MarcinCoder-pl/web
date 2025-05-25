<?php
define('ACCESS', true);
require_once __DIR__ . '/../tools/csrf_token.php';

session_start();

// Ochrona przed nieautoryzowanym dostępem
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['username'])) {
    header('Location: home.php');
    exit;
}

$csrf_token = generateCsrfToken(); // Generowanie tokena CSRF

// Dane do widoku
$sessionData = $_SESSION;
$sendStatus = null;

if (isset($_SESSION['wyslalno'])) {
    $sendStatus = $_SESSION['wyslalno'] === true ? 'ok' : 'nok';
    unset($_SESSION['wyslalno']); // Czyścimy sesję po wyświetleniu
}

// Załaduj widok
require __DIR__ . '/dashboard2.php';
