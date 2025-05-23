<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/get_page_content.php';

$slug = 'download';
$page = getPageContent($db, $slug, $language);
?>

<div class="post-container">
    <h1><?= $page['title'] ?></h1>
    <p><?= $page['content'] ?></p>
</div>
