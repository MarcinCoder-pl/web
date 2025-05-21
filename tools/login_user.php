<?php
// Ustawienia debugowania – tylko dla środowiska deweloperskiego!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicjalizacja sesji
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Stała bezpieczeństwa
define('ACCESS', true);

// Wczytanie konfiguracji i klas
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/sql_queries.php';
require_once __DIR__ . '/csrf_token.php';
require_once __DIR__ . '/ErrorMessageProvider.php';
require_once __DIR__ . '/validate_form_fields.php';

// Ustal język interfejsu
$lang = $_SESSION['lang'] ?? 'pl';
$db = new Database($db_host, $db_user, $db_password, $db_name);
$errorProvider = new ErrorMessageProvider($db, $lang);

// Obsługa tylko żądań POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = $errorProvider->getMessage('NO_POST_DATA');
    header('Location: ../index.php?strona=login');
    exit;
}

// CSRF
$csrf_token = $_POST['csrf_token'] ?? '';
if (!validateCsrfToken($csrf_token)) {
    $_SESSION['error'] = $errorProvider->getMessage('CSRF_ERROR');
    header('Location: ../index.php?strona=login');
    exit;
}

// Sprawdzenie obecności pól
if (!isset($_POST['username'], $_POST['password'])) {
    $_SESSION['error'] = $errorProvider->getMessage('MISSING_FIELDS');
    header('Location: ../index.php?strona=login');
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];

// Sprawdzenie długości
if (
    strlen($username) < MIN_LENGTH_REGISTER ||
    strlen($username) > MAX_LENGTH_REGISTER ||
    strlen($password) < MIN_LENGTH_REGISTER
) {
    $_SESSION['error'] = $errorProvider->getMessage('INVALID_LENGTH');
    header('Location: ../index.php?strona=login');
    exit;
}

// Sprawdzenie dozwolonych znaków
if (!isAlphanumeric($username)) {
    $_SESSION['error'] = $errorProvider->getMessage('INVALID_CHARS');
    header('Location: ../index.php?strona=login');
    exit;
}

// Pobranie połączenia i przygotowanie zapytania
$conn = $db->getConnection();

$stmt = $conn->prepare(LOGIN_ACC);
if (!$stmt) {
    $_SESSION['error'] = $errorProvider->getMessage('SQL_ERROR');
    header('Location: ../index.php?strona=login');
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($hashedPassword);

if ($stmt->fetch()) {
    if (password_verify($password, $hashedPassword)) {
        $_SESSION['username'] = $username;
        setcookie('username', $username, time() + 3600, "/", "", false, true); // httpOnly cookie
        $stmt->close();
        header('Location: ../index.php?strona=dashboard');
        exit;
    } else {
        $stmt->close(); // <- ważne: ZAMKNIJ zanim wywołasz getMessage()
        $_SESSION['error'] = $errorProvider->getMessage('INVALID_CREDENTIALS');
    }
} else {
    $stmt->close(); // <- również tutaj
    $_SESSION['error'] = $errorProvider->getMessage('INVALID_CREDENTIALS');
}

header('Location: ../index.php?strona=login');
exit;
?>
