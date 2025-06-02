<?php
define('ACCESS_DOOR', true);
require_once __DIR__ . '/../bootstrap.php';

use Tools_Manager\SessionManager;

$session = SessionManager::getInstance();
$session->logout();

header('Location: /?page=login');
exit;
