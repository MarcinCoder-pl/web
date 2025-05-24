<?php
require_once __DIR__ . '/../config/db_config.php';    // zmienne $db_host, $db_user, $db_password, $db_name
require_once __DIR__ . '/../config/database.php';     // klasa Database
require_once __DIR__ . '/../includes/get_page_content.php';
require_once __DIR__ . '/../config/lang_config.php';   // ustawia $current_lang

// Utworzenie instancji Database
try {
    $db = new Database($db_host, $db_user, $db_password, $db_name);
} catch (Exception $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}

 $slug = $_GET['znak'];// 'welcome';              // możesz zmienić lub pobrać z URL
$language = $current_lang;      // zdefiniowane w lang_config.php

$page = getPageContent($db, $slug, $language);
?>

<div class="post-container">
    <h1><?= htmlspecialchars($page['title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($page['content'])) ?></p>
</div>
