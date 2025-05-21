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
require_once __DIR__ . '/validate_form_fields.php';
require_once __DIR__ . '/csrf_token.php';
require_once __DIR__ . '/ErrorMessageProvider.php';

$langq =  $_SESSION['language'];
$db = new Database($db_host, $db_user, $db_password, $db_name);
$errorProvider = new ErrorMessageProvider($db, $langq);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($token)) {
        $_SESSION['error'] = $errorProvider->getMessage('CSRF_ERROR');
        header('Location: ../index.php?strona=register');
        exit;
    }

    if (!isset($_POST['username'], $_POST['password'], $_POST['password_confirm'])) {
        $_SESSION['error'] = $errorProvider->getMessage('MISSING_FIELDS');
        header('Location: ../index.php?strona=register');
        exit;
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (
        strlen($username) < MIN_LENGTH_REGISTER ||
        strlen($username) > MAX_LENGTH_REGISTER ||
        strlen($password) < MIN_LENGTH_REGISTER
    ) {
        $_SESSION['error'] = $errorProvider->getMessage('INVALID_LENGTH');
        header('Location: ../index.php?strona=register');
        exit;
    }

    if ($password !== $password_confirm) {
        $_SESSION['error'] = $errorProvider->getMessage('PASSWORDS_DO_NOT_MATCH');
        header('Location: ../index.php?strona=register');
        exit;
    }

    if (!isAlphanumeric($username)) {
        $_SESSION['error'] = $errorProvider->getMessage('INVALID_CHARS');
        header('Location: ../index.php?strona=register');
        exit;
    }

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Połączenie nie powiodło się: " . $conn->connect_error);
    }

    if ($db->isLoginTaken($username)) {
        $_SESSION['error'] = $errorProvider->getMessage('USERNAME_TAKEN');
        $conn->close();
        header('Location: ../index.php?strona=register');
        exit;
    }

    $hashed_pass = haszujHaslo($password);
    $stmt = $conn->prepare(ADD_ACC);
    $stmt->bind_param("ss", $username, $hashed_pass);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        setcookie('username', $username, time() + 3600, "/", false, true);
        $stmt->close();
        $conn->close();
        header('Location: /index.php?strona=dashboard');
        exit;
    } else {
        $_SESSION['error'] = $errorProvider->getMessage('SQL_ERROR');
        $stmt->close();
        $conn->close();
        header('Location: ../index.php?strona=register');
        exit;
    }
} else {
    $_SESSION['error'] = $errorProvider->getMessage('NO_POST_DATA');
    header('Location: ../index.php?strona=register');
    exit;
}
