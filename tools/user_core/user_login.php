<?php
define('ACCESS_DOOR', true);
require_once __DIR__ . '/../bootstrap.php';

use Tools_Manager\SessionManager;
use Tools_Network\ConfigLoader;
use Tools_Network\SqlQueryExecutor;
use Tools_Validation\FormValidator;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$session = SessionManager::getInstance();

// Walidacja CSRF
$csrfToken = $_POST['csrf_token'] ?? '';
if (!$session->validateCsrfToken('login', $csrfToken)) {
    die('Nieprawidłowy token CSRF.');
}

// Walidacja danych wejściowych
$validator = new FormValidator();
$validator->sanitize($_POST);

$rules = [
    'username' => ['required' => true, 'min' => 3, 'max' => 255],
    'password' => ['required' => true, 'min' => 6, 'max' => 255],
];

if (!$validator->validate($rules)) {
    echo "Błędy formularza:";
    foreach ($validator->getErrors() as $field => $messages) {
        foreach ($messages as $msg) {
            echo "<p>$field: $msg</p>";
        }
    }
    exit;
}

$data = $validator->getSanitizedData();
$usernameOrEmail = $data['username'];
$password = $data['password'];

try {
    $config = new ConfigLoader(__DIR__ . '/../config.ini');
    $db = $config->createDatabase();
    $query = new SqlQueryExecutor($db);

    $user = $query->getUserByUsernameOrEmail($usernameOrEmail);

    if (!$user || !isset($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
        $session->set('login_error', 'Nieprawidłowy login lub hasło.');
        header("Location: /?page=login");
        exit;
    }

    // Sprawdzenie, czy konto aktywne
    if ((int)$user['is_active'] !== 1) {
        $session->set('login_error', 'Konto jest nieaktywne.');
        header("Location: /?page=login");
        exit;
    }

    $session->login($user['id'], $user['username']);

    // Przekierowanie po sukcesie
    header("Location: /?page=dashboard");
    exit;
} catch (Exception $e) {
    $session->set('login_error', 'Błąd systemu: ' . $e->getMessage());
    header("Location: /?page=login");
    exit;
}
