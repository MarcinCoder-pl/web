<?php
use Tools_Manager\SessionManager;

$session = SessionManager::getInstance();

if (!$session->isLoggedIn()) {
    header('Location: /?page=login');
    exit;
}

$userId = $session->get('user_id');
$username = $session->get('username'); // Zakładamy, że zapisałeś nazwę użytkownika w sesji po zalogowaniu/rejestracji
?>

<h2>Witaj w panelu użytkownika</h2>

<p>Cześć, <strong><?= htmlspecialchars($username ?? 'Użytkowniku') ?></strong>! Miło Cię widzieć.</p>

<ul>
    <li><a href="/?page=profile">Twój profil</a></li>
    <li><a href="/?page=settings">Ustawienia konta</a></li>
    <li><a href="/../tools/user_core/user_logout.php">Wyloguj się</a></li>
</ul>
