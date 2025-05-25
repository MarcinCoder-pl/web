<?php
define('ACCESS', true);
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/MessageReceiver.php';

if (!isset($_SESSION['user_id'])) {
    die('Nie jesteś zalogowany.');
}

$db = new Database($db_host, $db_user, $db_password, $db_name);
$messageReceiver = new MessageReceiver($db);
$userId = $_SESSION['user_id'];
$messageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    if ($messageId > 0) {
        $message = $messageReceiver->getMessageById($messageId, $userId);
        if ($message) {
            $messageReceiver->markAsRead($messageId, $userId);
            echo "<h2>{$message['subject']}</h2>";
            echo "<p><strong>Od:</strong> {$message['sender_username']}</p>";
            echo "<p><strong>Treść:</strong> {$message['body']}</p>";
            echo "<p><small>Data: {$message['created_at']}</small></p>";
        } else {
            echo "Wiadomość nie znaleziona.";
        }
    } else {
        $messages = $messageReceiver->getReceivedMessages($userId, 20, 0);
	echo "<h3>" . htmlspecialchars($_SESSION['tab_lang'][0]) . ":</h3><ul>";
        foreach ($messages as $msg) {
            $readClass = $msg['is_read'] ? '' : 'style="font-weight: bold;"';
            echo "<li {$readClass}><a href='view_messages.php?id={$msg['id']}'>" . 
                 htmlspecialchars($msg['subject']) . 
                 " od " . htmlspecialchars($msg['sender_username']) . "</a></li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "Błąd: " . $e->getMessage();
}
?>
