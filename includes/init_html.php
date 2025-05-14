<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
	require_once __DIR__ . '/../config/lang_config.php'; 
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $lang['title'] ?></title>
    <?php $_SESSION['language'] = $current_lang; ?> 
    <link rel="icon" href="image/favicon-32x32.png" type="image/x-icon">
    
    <!-- Opis strony dla wyszukiwarek (SEO) -->
    <meta name="description" content="Krótki opis strony"> 
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Meta tagi społecznościowe (np. Facebook, Twitter) -->
    <meta property="og:title" content="Tytuł na Facebooku">
    <meta property="og:description" content="Opis na Facebooku">
    <meta property="og:image" content="adres_do_obrazka.jpg">
    
    <!-- Słowa kluczowe dla wyszukiwarek -->
    <meta name="keywords" content="słowo1, słowo2, słowo3">
</head>
