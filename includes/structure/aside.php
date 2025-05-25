<?php
// W pliku, który chcesz chronić, dodaj na górze coś takiego:
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}
$_SESSION['tab_lang'] = [
	$lang['received_messages'],
	$lang['odkogo'] ?? 'brak'
];
if (isset($_SESSION['user_id'])) {
    // Zakładam, że $lang['message'] zawiera tekst do wyświetlenia
    echo '<a href="../tools/view_messages.php">📨 ' . htmlspecialchars($lang['message']) . '</a>';
    echo '<a href="../tools/send_message.html">📨 ' . htmlspecialchars($lang['message_send']) . '</a>';
}
?>

Online server Offline
