<?php
define('ACCESS_DOOR', true);
require_once __DIR__ . '/../bootstrap.php';

use Tools_Validation\FormValidator;
use Tools_Manager\SessionManager;
use Tools_Network\ConfigLoader;

$session = SessionManager::getInstance();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Niedozwolona metoda.');
}

$csrfToken = $_POST['csrf_token'] ?? '';
if (!$session->validateCsrfToken('report_bug', $csrfToken)) {
    http_response_code(403);
    exit('Nieprawidłowy token CSRF.');
}

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
    header('Location: /?page=report_bug');
    exit;
}

$data = $validator->getSanitizedData();

try {
    $config = new ConfigLoader(__DIR__ . '/../config.ini');
    $db = $config->createDatabase();

    $query = "INSERT INTO bug_reports (name, email, description, created_at) VALUES (?, ?, ?, NOW())";
    $params = [$data['name'], $data['email'], $data['message']];
    $db->insert($query, $params);

    $session->set('form_success', 'Zgłoszenie zostało wysłane.');
    header('Location: /?page=report_bug');
    exit;
} catch (Exception $e) {
    $session->set('form_errors', [
        'general' => ['Błąd podczas zapisywania zgłoszenia: ' . $e->getMessage()]
    ]);
    header('Location: /?page=report_bug');
    exit;
}
