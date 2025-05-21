<?php
$host = 'localhost';       // lub adres serwera bazy danych
$user = 'nowy_user';       // nazwa użytkownika MySQL
$password = 'twoje_haslo'; // hasło użytkownika
$database = 'db_databases';

// Tworzenie połączenia
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    // Ustawienie trybu błędów na wyjątki
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Połączenie z bazą danych zostało nawiązane pomyślnie!";
} catch (PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
}
?>

