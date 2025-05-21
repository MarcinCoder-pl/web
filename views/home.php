<?php
// Zabezpieczenie dostępu
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

// Wczytanie wymaganych plików
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/sql_queries.php';
require_once __DIR__ . '/../config/database.php';

try {
    // Inicjalizacja bazy danych
    $db = new Database($db_host, $db_user, $db_password, $db_name);

    // Dane wejściowe
    $slug = 'welcome';
    $languageCode = $lang['language'];

    // Sprawdzenie, czy zapytanie zostało zdefiniowane
    if (!defined('GET_POST_WEB_HOME')) {
        throw new Exception("Zapytanie GET_POST_WEB_DOWNLOAD nie zostało zdefiniowane.");
    }

    // Wykonanie zapytania przy użyciu metody prepareAndExecute()
    $result = $db->prepareAndExecute(GET_POST_WEB_DOWNLOAD, [$slug, $languageCode]);

    ?>

    <div class="post-container">
        <?php if ($post = $result->fetch_assoc()): ?>
            <h1><?= htmlspecialchars($post['title']) ?></h1>
            <div class="download-button-wrapper">
                <a href="files/game_installer_windows.exe" class="download-button" download>
                    ⬇️ Download Game for Windows
                </a>
            </div>
            <div><?= nl2br(htmlspecialchars($post['content'])) ?></div>
        <?php else: ?>
            <p>Nie znaleziono posta o slug: <strong><?= htmlspecialchars($slug) ?></strong>
            w języku: <strong><?= htmlspecialchars($languageCode) ?></strong>.</p>
        <?php endif; ?>
    </div>

    <?php
} catch (Exception $e) {
    echo "<p style='color:red;'>Błąd: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
