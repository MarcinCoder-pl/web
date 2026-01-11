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

/* =======================
   CSRF
======================= */
$csrfToken = $_POST['csrf_token'] ?? '';
if (!$session->validateCsrfToken('login', $csrfToken)) {
    $session->set('login_error', $session->get('login_error_invalid_csrf'));
    header("Location: /?page=login");
    exit;
}

/* =======================
   WALIDACJA
======================= */
$validator = new FormValidator();
$validator->sanitize($_POST);

$rules = [
    'username' => ['required' => true, 'min' => 3, 'max' => 255],
    'password' => ['required' => true, 'min' => 6, 'max' => 255],
];

if (!$validator->validate($rules)) {
    $errors = $validator->getErrors();

    foreach ($errors as $field => &$messages) {
        foreach ($messages as &$msg) {
            if ($field === 'username') {
                if (str_contains($msg, 'required')) $msg = $session->get('login_validation_username_required');
                if (str_contains($msg, 'min'))      $msg = $session->get('login_validation_username_min');
                if (str_contains($msg, 'max'))      $msg = $session->get('login_validation_username_max');
            }

            if ($field === 'password') {
                if (str_contains($msg, 'required')) $msg = $session->get('login_validation_password_required');
                if (str_contains($msg, 'min'))      $msg = $session->get('login_validation_password_min');
                if (str_contains($msg, 'max'))      $msg = $session->get('login_validation_password_max');
            }
        }
    }

    $session->set('login_error', $errors);
    header("Location: /?page=login");
    exit;
}

/* =======================
   LOGOWANIE
======================= */
$data = $validator->getSanitizedData();

try {
    $config = new ConfigLoader(__DIR__ . '/../config.ini');
    $db = $config->createDatabase();
    $query = new SqlQueryExecutor($db);

    $user = $query->getUserByUsernameOrEmail($data['username']);

    if (!$user || !password_verify($data['password'], $user['password_hash'])) {
        $session->set('login_error', $session->get('login_error_invalid_credentials'));
        header("Location: /?page=login");
        exit;
    }

    if ((int)$user['is_active'] !== 1) {
        $session->set('login_error', $session->get('login_error_account_inactive'));
        header("Location: /?page=login");
        exit;
    }

    $session->login($user['id'], $user['username']);
    header("Location: /?page=dashboard");
    exit;

} catch (Exception $e) {
    $msg = str_replace(
        '{error_message}',
        $e->getMessage(),
        $session->get('login_error_system')
    );

    $session->set('login_error', $msg);
    header("Location: /?page=login");
    exit;
}
