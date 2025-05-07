<?php

session_start();

// Domyślny język
$default_lang = 'pl';

// Zmiana języka przez GET
if (isset($_GET['lang'])) {
    $lang_code = $_GET['lang'];
    $_SESSION['lang'] = $lang_code;
}

// Ustaw język z sesji albo domyślny
$current_lang = $_SESSION['lang'] ?? $default_lang;

// Wczytaj plik językowy
$lang_file = __DIR__ . '/../lang/' . $current_lang . '.php';
if (file_exists($lang_file)) {
    include $lang_file;
} else {
    include __DIR__ . '/../lang/' . $default_lang . '.php';
}
