<?php 
// Sprawdzamy, czy sesja jest już uruchomiona
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
define('ACCESS', true);
$page = isset($_GET['strona']) ? $_GET['strona'] : 'home';

require_once 'includes/init_html.php';  // Zaczyna nagłówek HTML
require_once 'includes/body_wrapper.php';       // Zawartość strony

?>
