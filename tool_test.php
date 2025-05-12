<?php
$host = 'localhost'; // host bazy danych
$dbname = 'db_user_web'; // nazwa bazy danych
$user = 'webuser'; // użytkownik bazy danych
$password = 'haslo123'; // hasło do bazy danych

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

