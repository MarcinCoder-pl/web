<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ACCESS', true);
require_once __DIR__ . '/AuditLogger.php';
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/sql_queries.php';
require_once __DIR__ . '/validate_form_fields.php';
require_once __DIR__ . '/csrf_token.php';
require_once __DIR__ . '/ErrorMessageProvider.php';

$langq = $_SESSION['language'] ?? 'pl';
$db = new Database($db_host, $db_user, $db_password, $db_name);
$errorProvider = new ErrorMessageProvider($db, $langq);
$auditLogger = new AuditLogger($db);

$ip = $_SERVER['REMOTE_ADDR'];
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$registrationSuccess = false;
$userId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($token)) {
        $_SESSION['error'] = $errorProvider->getMessage('CSRF_ERROR');
        goto finish;
    }

    if (!isset($_POST['username'], $_POST['password'], $_POST['password_confirm'], $_POST['email'])) {
        $_SESSION['error'] = $errorProvider->getMessage('MISSING_FIELDS');
        goto finish;
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $email = trim($_POST['email']);

    if (
        strlen($username) < MIN_LENGTH_REGISTER ||
        strlen($username) > MAX_LENGTH_REGISTER ||
        strlen($password) < MIN_LENGTH_REGISTER
    ) {
        $_SESSION['error'] = $errorProvider->getMessage('INVALID_LENGTH');
        goto finish;
    }

    if ($password !== $password_confirm) {
        $_SESSION['error'] = $errorProvider->getMessage('PASSWORDS_DO_NOT_MATCH');
        goto finish;
    }

    if (!isAlphanumeric($username)) {
        $_SESSION['error'] = $errorProvider->getMessage('INVALID_CHARS');
        goto finish;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = $errorProvider->getMessage('INVALID_EMAIL');
        goto finish;
    }

    if ($db->isLoginTaken($username)) {
        $_SESSION['error'] = $errorProvider->getMessage('USERNAME_TAKEN');
        goto finish;
    }

    if ($db->isEmailTaken($email)) {
        $_SESSION['error'] = $errorProvider->getMessage('EMAIL_TAKEN');
        goto finish;
    }

    $hashed_pass = haszujHaslo($password);
    $conn = $db->getConnection();
    $stmt = $conn->prepare(ADD_ACC);
    $stmt->bind_param("sss", $username, $hashed_pass, $email);

    if ($stmt->execute()) {
        $userId = $stmt->insert_id;
        $_SESSION['username'] = $username;
        setcookie('username', $username, time() + 3600, "/", "", false, true);
        $registrationSuccess = true;
        $userId = $stmt->insert_id;
            $auditLogger->log($userId, 'register', "Rejestracja z email: {$email}, IP: {$ip}");
    } else {
        $_SESSION['error'] = $errorProvider->getMessage('SQL_ERROR');
    }

    $stmt->close();
} else {
    $_SESSION['error'] = $errorProvider->getMessage('NO_POST_DATA');
}

// Zarejestruj próbę (success/failure) z user_id, jeśli się udało
finish:
$db->prepareAndExecute(
    "INSERT INTO login_attempts (user_id, email_or_username, ip_address, success) VALUES (?, ?, ?, ?)",
    [$registrationSuccess ? $userId : null, $username ?: $email, $ip, $registrationSuccess ? 1 : 0]
);

header('Location: ../index.php?strona=' . ($registrationSuccess ? 'dashboard' : 'register'));
exit;
