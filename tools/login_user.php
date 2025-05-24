<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ACCESS', true);

require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/sql_queries.php';
require_once __DIR__ . '/csrf_token.php';
require_once __DIR__ . '/ErrorMessageProvider.php';
require_once __DIR__ . '/validate_form_fields.php';
require_once __DIR__ . '/LoginAttemptStats.php';
require_once __DIR__ . '/BruteForceProtector.php';

$lang = $_SESSION['lang'] ?? 'pl';
$db = new Database($db_host, $db_user, $db_password, $db_name);
$errorProvider = new ErrorMessageProvider($db, $lang);
$stats = new LoginAttemptStats($db);
$protector = new BruteForceProtector($stats);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = $errorProvider->getMessage('NO_POST_DATA');
    header('Location: ../index.php?strona=login');
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!validateCsrfToken($csrf_token)) {
    $_SESSION['error'] = $errorProvider->getMessage('CSRF_ERROR');
    header('Location: ../index.php?strona=login');
    exit;
}

if (empty($_POST['username']) || empty($_POST['password'])) {
    $_SESSION['error'] = $errorProvider->getMessage('MISSING_FIELDS');
    header('Location: ../index.php?strona=login');
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];
$ip = $_SERVER['REMOTE_ADDR'];

if ($protector->isBlocked($ip, $username)) {
    $remaining = $protector->getRemainingBlockTime($ip, $username);
    $_SESSION['error'] = $errorProvider->getMessage('TOO_MANY_ATTEMPTS') . " Spróbuj ponownie za {$remaining} sekund.";
    header('Location: ../index.php?strona=login');
    exit;
}

if (
    strlen($username) < MIN_LENGTH_REGISTER ||
    strlen($username) > MAX_LENGTH_REGISTER ||
    strlen($password) < MIN_LENGTH_REGISTER
) {
    $_SESSION['error'] = $errorProvider->getMessage('INVALID_LENGTH');
    header('Location: ../index.php?strona=login');
    exit;
}

if (!isAlphanumeric($username)) {
    $_SESSION['error'] = $errorProvider->getMessage('INVALID_CHARS');
    header('Location: ../index.php?strona=login');
    exit;
}

$conn = $db->getConnection();
$stmt = $conn->prepare(LOGIN_ACC);
if (!$stmt) {
    $_SESSION['error'] = $errorProvider->getMessage('SQL_ERROR');
    header('Location: ../index.php?strona=login');
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($hashedPassword);

$loginSuccess = false;
if ($stmt->fetch()) {
    if (password_verify($password, $hashedPassword)) {
        session_regenerate_id(true);
        $_SESSION['username'] = $username;
        setcookie('username', $username, time() + 3600, "/", "", false, true);
        $loginSuccess = true;
    } else {
        $_SESSION['error'] = $errorProvider->getMessage('INVALID_CREDENTIALS');
    }
}
$stmt->close();

$db->prepareAndExecute(
    "INSERT INTO login_attempts (username, ip_address, success) VALUES (?, ?, ?)",
    [$username, $ip, $loginSuccess ? 1 : 0]
);

header('Location: ../index.php?strona=' . ($loginSuccess ? 'dashboard' : 'login'));
exit;
?>
