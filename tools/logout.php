<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
define('ACCESS', true);
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/csrf_token.php';
require_once __DIR__ . '/AuditLogger.php';



// Inicjalizacja bazy danych i loggera audytu
$db = new Database($db_host, $db_user, $db_password, $db_name);
$auditLogger = new AuditLogger($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (validateCsrfToken($token)) {
        $_SESSION['csrf_token'] = 'X';

        // Logowanie audytu (jeśli użytkownik zalogowany)
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $details = "Wylogowanie z IP: $ip";
            $auditLogger->log($userId, 'logout', $details);
        }

        // Usuwanie danych sesji
        session_unset();
        session_destroy();

        // Usuwanie ciasteczka sesji z przeglądarki
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // (opcjonalnie) Usunięcie innych własnych ciasteczek
        setcookie('username', '', time() - 3600, '/');

        // Przekierowanie na stronę główną
        header('Location: ../index.php?strona=home');
        exit;
    } else {
        echo "Nieprawidłowy token CSRF!";
        exit;
    }
}

// Jeśli żądanie nie jest POST
header('Location: ../index.php?strona=home');
exit;
