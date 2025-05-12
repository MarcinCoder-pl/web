<?php
$db_host = 'localhost';
$db_name = 'db_user_web';
$db_user = 'user_web';
$db_password = 'haslo';

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Połączono z bazą danych!";
} catch (PDOException $e) {
    echo "Błąd połączenia: " . $e->getMessage();
}

