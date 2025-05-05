<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/csrf_token.php';  // Plik z funkcją do generowania i walidacji tokena CSRF

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (validateCsrfToken($token)) {
        $_SESSION['csrf_token'] = 'X';

        // Usuwanie danych sesji
        session_unset();

        // Zniszczenie sesji
        session_destroy();

        // Usuwanie ciasteczka sesji z przeglądarki
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // (opcjonalne) Usunięcie innych własnych ciasteczek
        setcookie('username', '', time() - 3600, '/');

        // Przekierowanie na stronę logowania lub główną
        header('Location: ../index.php?strona=home');
        exit;
    } else {
        echo "Nieprawidłowy token CSRF!";
        exit;
    }
}

// Jeśli żądanie nie jest typu POST
header('Location: ../index.php?strona=home');
exit;
?>
