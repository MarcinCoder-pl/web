<?php
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/sql_queries.php';

$slug = 'contact';
$languageCode = $lang['language']; // np. 'pl', 'cs', 'en'

try {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    $conn->set_charset('utf8mb4');

    if (!defined('GET_POST_BY_WEBSITE')) {
        throw new Exception("Stała GET_POST_BY_WEBSITE nie została zdefiniowana.");
    }

    $stmt = $conn->prepare(GET_POST_BY_WEBSITE);
    $stmt->bind_param("ss", $slug, $languageCode);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="post-container">
        <?php
        if ($post = $result->fetch_assoc()) {
            echo "<h1>" . htmlspecialchars($post['title']) . "</h1>";
            echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";

            if (!empty($post['wsparcie'])) {
                echo "<p><strong>" . htmlspecialchars($post['wsparcie']) . ":</strong></p>";
            }
            if (!empty($post['partnerzy_media'])) {
                echo "<p><strong>" . htmlspecialchars($post['partnerzy_media']) . ":</strong></p>";
            }
            if (!empty($post['adres_korespondencyjny'])) {
                echo "<p><strong>" . htmlspecialchars($post['adres_korespondencyjny']) . ":</strong> </p>";
            }
        } else {
            echo "<p>Nie znaleziono posta o slug: <strong>$slug</strong> w języku: <strong>$languageCode</strong>.</p>";
        }
        ?>
    </div>

    <?php
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo "<p style='color:red;'>Błąd: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
