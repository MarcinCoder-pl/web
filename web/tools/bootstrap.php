<?php
if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
}

// Autoloader dla klas z przestrzeni nazw
spl_autoload_register(function ($className) {
    $baseDir = __DIR__ . '/'; // katalog /tools/

    // Zamień namespace na ścieżkę do pliku
    $relativePath = str_replace('\\', '/', $className) . '.php';

    // Pełna ścieżka
    $file = $baseDir . $relativePath;

    if (file_exists($file)) {
        require_once $file;
    }
});
