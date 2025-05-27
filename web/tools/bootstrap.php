<?php
// tools/bootstrap.php

spl_autoload_register(function ($className) {
    $baseDir = dirname(__DIR__); // katalog główny projektu

    // Zamień backslashe z namespace na slashe do folderów
    $relativePath = str_replace('\\', '/', $className) . '.php';

    // Lista folderów, gdzie szukamy klas
    $directories = [
        '/includes/',
        '/models/',
        '/controllers/',
        '/tools/',
    ];

    foreach ($directories as $dir) {
        $file = $baseDir . $dir . $relativePath;
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
