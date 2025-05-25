<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/csrf_token.php';
require_once __DIR__ . '/MessageReceiver.php';
require_once __DIR__ . '/MessageSender.php';


$csrf_token = generateCsrfToken();

?>

<form method="post" action="send_message.php">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
    <!-- inne pola: receiver_id, subject, body -->
</form>
