<?php
define('ACCESS', true);

// Ładowanie konfiguracji
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/sql_queries.php';
require_once __DIR__ . '/../config/database.php';

// Dane wejściowe
$slug = 'download';
$languageCode = $lang['language'] ?? 'pl';

try {
    $db = new Database($db_host, $db_user, $db_password, $db_name);
    $result = $db->getPostBySlugAndLang($slug, $languageCode);
    ?>

    <div class="post-container">
        <?php if ($post = $result->fetch_assoc()): ?>
            <h1><?= htmlspecialchars($post['title']) ?></h1>
            <div class="download-button-wrapper">
                <a href="files/game_installer_windows.exe" class="download-button" download>
                    ⬇️ Download Game for Windows
                </a>
            </div>
            <div><?= nl2br(htmlspecialchars($post['content'])) ?></div><a href="#">a</a>
        <?php else: ?>
            <p>Nie znaleziono posta o slug: <strong><?= htmlspecialchars($slug) ?></strong>
            w języku: <strong><?= htmlspecialchars($languageCode) ?></strong>.</p>
        <?php endif; ?>
    </div>

    <?php
    $db->close();
} catch (Exception $e) {
    echo "<p style='color:red;'>Błąd: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
