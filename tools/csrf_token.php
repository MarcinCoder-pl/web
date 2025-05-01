<?php
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

function generateCsrfToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;

    return $token;
}

function validateCsrfToken($token): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Sprawdź, czy token jest typu string i nie jest pusty
    if (!is_string($token) || empty($token)) {
        return false;
    }

    // Usuń zbędne białe znaki
    $token = trim($token);

    // CSRF token powinien mieć długość 64 znaków (32 bajty w hexie)
    if (strlen($token) !== 64) {
        return false;
    }

    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

?>
