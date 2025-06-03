<?php
define('ACCESS_DOOR', true);

require_once __DIR__ . '/../bootstrap.php';

use Tools_Validation\FormValidator;
use Tools_Manager\SessionManager;
use Tools_Network\ConfigLoader;

// Start sesji i walidacja CSRF
$session = SessionManager::getInstance();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Niedozwolona metoda.');
}

$csrfToken = $_POST['csrf_token'] ?? '';
if (!$session->validateCsrfToken('send_message', $csrfToken)) {
    http_response_code(403);
    exit('Nieprawidłowy token CSRF.');
}

// Walidacja formularza
$validator = new FormValidator();
$validator->sanitize($_POST);

$rules = [
    'name' => ['required' => true, 'min' => 2, 'max' => 100],
    'email' => ['required' => true, 'email' => true, 'max' => 150],
    'message' => ['required' => true, 'min' => 10, 'max' => 2000],
];

if (!$validator->validate($rules)) {
    $session->set('form_errors', $validator->getErrors());
    $session->set('form_data', $_POST);
    header('Location: /?page=contact');
    exit;
}

$data = $validator->getSanitizedData();

try {
    $config = new ConfigLoader(__DIR__ . '/../config.ini');
    $db = $config->createDatabase();

$query = "INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())";
$params = [
    $data['name'],
    $data['email'],
    $data['message']
];

    $db->insert($query, $params);

    $session->set('form_success', 'Wiadomość została pomyślnie wysłana.');
    header('Location: /?page=contact');
    exit;
}  catch (Exception $e) {
    $session->set('form_errors', [
        'general' => ['Wystąpił błąd podczas wysyłania wiadomości: ' . $e->getMessage()]
    ]);
    header('Location: /?page=contact');
    exit;
}

