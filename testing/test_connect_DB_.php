<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ścieżka do pliku INI z konfiguracją bazy
$config = parse_ini_file(__DIR__ . '/tools/config.ini', true);

if (!$config || !isset($config['database'])) {
    die("Nie można wczytać konfiguracji z pliku INI.");
}

$db = $config['database'];

// Budowanie DSN
$dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $db['username'], $db['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Przykładowe zapytanie
    $stmt = $pdo->query("SELECT NOW() AS czas");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "Połączono z bazą danych. Czas: " . $row['czas'];
} catch (PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
}
