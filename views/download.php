
<?php
define('ACCESS', true);

// Załaduj konfigurację i potrzebne pliki
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/sql_queries.php';


// Ustaw domyślny język (można to zmieniać np. przez sesję lub URL)
$language = $_SESSION['language'];

try {
    // Połącz z bazą danych
    $db = new Database($db_host, $db_user, $db_password, $db_name);

    // Pobierz zawartość strony powitalnej
    $slug = 'download';
    $result = $db->prepareAndExecute(GET_POST_WEB, [$slug, $language]);

    if ($result && $row = $result->fetch_assoc()) {
        $title = htmlspecialchars($row['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $content = nl2br(htmlspecialchars($row['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    } else {
        $title = "Strona główna";
        $content = "Nie znaleziono zawartości dla tego języka.";
    }

} catch (Exception $e) {
    $title = "Błąd";
    $content = "Wystąpił błąd: " . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
?>

       <div class="post-container">
        <h1><?= $title ?></h1>
                    <div class="download-button-wrapper">
                <a href="files/game_installer_windows.exe" class="download-button" download>
                    ⬇️ Download Game for Windows
                </a>
            </div>
        <p><?= $content ?></p>
    </div>

