<?php
// Zabezpieczenie dostępu
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

// Połączenie z bazą danych
require_once __DIR__ . '/../config/db_config.php';

// Ustawienie danych wejściowych
$slug = 'welcome';

// Przykładowy język – powinieneś wcześniej ustawić $lang['language'] gdzieś globalnie
// Zakładamy że masz coś takiego np. w pliku językowym
//$lang['language'] = 'pl'; // lub 'en', 'de', itd.
$languageCode = $lang['language'];

// Włącz raportowanie błędów MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Połączenie z bazą
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    $conn->set_charset('utf8mb4');

    // Przygotowanie zapytania
    if (!defined('GET_POST_BY_SLUG_AND_LANG')) {
        throw new Exception("Stała GET_POST_BY_SLUG_AND_LANG nie została zdefiniowana.");
    }

    $stmt = $conn->prepare(GET_POST_BY_SLUG_AND_LANG);
    $stmt->bind_param("ss", $slug, $languageCode);
    $stmt->execute();
    $result = $stmt->get_result();

    // Pobranie posta
    if ($post = $result->fetch_assoc()) {
        echo "<h1>" . htmlspecialchars($post['title']) . "</h1>";
        echo "<div>" . nl2br(htmlspecialchars($post['content'])) . "</div>";
    } else {
        echo "<p>Nie znaleziono posta o slug: <strong>$slug</strong> w języku: <strong>$languageCode</strong>.</p>";
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo "<p style='color:red;'>Błąd: " . htmlspecialchars($e->getMessage()) . "</p>";
}
