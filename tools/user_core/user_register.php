<?php
define('ACCESS_DOOR', true);
require_once __DIR__ . '/../bootstrap.php'; // autoloader i definicje
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Tools_Manager\SessionManager;
use Tools_Validation\FormValidator;
use Tools_Network\ConfigLoader;

$session = SessionManager::getInstance();

// CSRF token validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['csrf_token']) || !$session->validateCsrfToken('register', $_POST['csrf_token'])) {
    $session->set('register_error', 'Nieprawidłowy token bezpieczeństwa.');
    header('Location: /?page=register');
    exit;
}

// Walidacja i sanitizacja formularza
$validator = new FormValidator();
$validator->sanitize($_POST);

$rules = [
    'username' => [
        'required' => true,
        'pattern' => '/^[a-zA-Z0-9_]+$/',
        'min' => 3,
        'max' => 20
    ],
    'email' => [
        'required' => true,
        'email' => true
    ],
    'password' => [
        'required' => true,
        'min' => 6,
        'max' => 50
    ],
    'confirm_password' => [
        'required' => true
    ]
];


if (!$validator->validate($rules)) {
    $session->set('register_error', 'Nieprawidłowe dane formularza.');
    header('Location: /?page=register');
    exit;
}

$data = $validator->getSanitizedData();

if ($data['password'] !== $data['confirm_password']) {
    $session->set('register_error', 'Hasła nie są takie same.');
    header('Location: /?page=register');
    exit;
}

// Połączenie z bazą danych
$config = new ConfigLoader(__DIR__ . '/../config.ini');
$db = $config->createDatabase();

// Sprawdzenie czy login już istnieje — zmiana :username na ? i parametry w tablicy pozycyjnej
$existing = $db->selectOne('SELECT id FROM users WHERE username = ?', [
    $data['username']
]);

if ($existing) {
    $session->set('register_error', 'Użytkownik o takim loginie już istnieje.');
    header('Location: /?page=register');
    exit;
}

// Rejestracja
$passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

$existingEmail = $db->selectOne('SELECT id FROM users WHERE email = ?', [$data['email']]);
if ($existingEmail) {
    $session->set('register_error', 'Użytkownik z takim adresem email już istnieje.');
    header('Location: /?page=register');
    exit;
}


$inserted = $db->insert('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)', [
    $data['username'],
    $data['email'],
    $passwordHash
]);

if (!$inserted) {
    $session->set('register_error', 'Błąd podczas rejestracji.');
    header('Location: /?page=register');
    exit;
}

// Zaloguj użytkownika używając ID zwróconego przez insert()
$session->login($inserted, $data['username']);
header('Location: /?page=dashboard');
exit;