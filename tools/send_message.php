<?php
define('ACCESS', true);
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/csrf_token.php';
require_once __DIR__ . '/MessageReceiver.php';
require_once __DIR__ . '/MessageSender.php';
$_SESSION['wyslalno']= false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if (!validateCsrfToken($_POST['csrf_token'] ?? ''))
    {
        die('Błąd bezpieczeństwa: niepoprawny token CSRF.');
    }

    $db = new Database($db_host, $db_user, $db_password, $db_name);
    $messageSender = new MessageSender($db);

    $senderId = $_SESSION['user_id'];
    $userId = $messageSender->getUserIdByUsername($_POST['receiver_id']);
	if ($userId !== null)
    {
		$receiverId = intval($userId);
		$subject = trim($_POST['subject'] ?? '');
		$body = trim($_POST['body'] ?? '');

		try {
			$messageSender->sendMessage($senderId, $receiverId, $subject, $body);
			
			// PRZEKIEROWANIE NA STRONĘ GŁÓWNĄ PO SUKCESIE
			header('Location: /index.php?strona=dashboard'); // dostosuj ścieżkę jeśli strona główna ma inną lokalizację
			$_SESSION['wyslalno'] = true;
			exit();
		} catch (Exception $e) {
			echo "Błąd: " . $e->getMessage();
		}
		
	}
	else {
			header('Location: /index.php?strona=dashboard'); // dostosuj ścieżkę jeśli strona główna ma inną lokalizację
			$_SESSION['wyslalno']= false;
			exit();
	}

}
