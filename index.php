<?php
header("Referrer-Policy: strict-origin-when-cross-origin");

use Tools_Manager\SessionManager;
use Tools_Controller\SimplePageRouter;
use Tools_Controller\LanguageManager;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ACCESS_DOOR', true);

// Ładowanie klas
require_once __DIR__ . '/tools/bootstrap.php';

// Inicjalizacja menedżera języków
$languageManager = new LanguageManager(__DIR__ . '/lang');
$translations = $languageManager->getTranslations();

// Inicjalizacja sesji i ustawienie tłumaczeń
$session = SessionManager::getInstance();
$session->set($translations);

// Routing widoku
$viewsPath = __DIR__ . '/views';
$route = new SimplePageRouter($viewsPath, $languageManager);

// Aktualny język
$currentLang = $languageManager->getCurrentLang();

// Ładowanie HTML
require_once __DIR__ . '/includes/init_html.php';
