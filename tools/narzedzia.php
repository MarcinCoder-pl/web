<?php
if (!defined('ACCESS')) {
    die('Brak dostępu.');
    header('Location: /index.php?strona=home');
}

?>
if (!defined('ACCESS')) {
    http_response_code(403); // Błąd 403: Forbidden
    echo 'Brak dostępu.';
    exit;
}
