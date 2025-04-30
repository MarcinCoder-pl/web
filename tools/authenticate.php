<?php
// Rozpoczynamy sesję
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Stała do ochrony plików
define('ACCESS', true);

// Dołączenie konfiguracji i funkcji pomocniczych
require_once '../config/database_conf.php'; // Połączenie z bazą danych
require_once 'check_acc.php';

// Minimalna długość loginu
define('MIN_SIZE', 10);

// Generowanie tokena CSRF, jeśli jeszcze nie istnieje
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Sprawdzenie czy formularz został przesłany i zawiera wszystkie dane
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['username'], $_POST['password'], $_POST['password_confirm'], $_POST['csrf_token']) &&
    strlen($_POST['username']) >= MIN_SIZE
) {
    // Sprawdzenie poprawności tokena CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Nieprawidłowy token CSRF.');
    }

    $username_input = convert_to_utf8($_POST['username']);
    $password_input = $_POST['password'];
    $password_confirm_input = $_POST['password_confirm'];

    // Sprawdzenie, czy hasła są takie same
    if ($password_input !== $password_confirm_input) {
        die('Hasła nie są takie same.');
    }

    // Walidacja loginu
    if (!isAlphanumeric($username_input)) {
        die('Login może zawierać tylko litery i cyfry.');
    }

    // Nawiązywanie połączenia z bazą danych
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Połączenie nie powiodło się: " . $conn->connect_error);
    }

    // Sprawdzenie, czy login już istnieje
    if (isLoginTaken($conn, $username_input)) {
        $conn->close();
        die('Użytkownik już istnieje.');
    }

    // Haszowanie hasła
    $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

    // Wstawienie użytkownika do bazy
    $sql = "INSERT INTO uzytkownicy (login, haslo) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $conn->close();
        die("Błąd przygotowania zapytania: " . $conn->error);
    }

    $stmt->bind_param("ss", $username_input, $hashed_password);

    if ($stmt->execute()) {
        echo "Konto zostało utworzone pomyślnie!";
        // Odnów token po sukcesie
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } else {
        echo "Błąd: " . $stmt->error;
    }

    // Sprzątanie
    $stmt->close();
    $conn->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    die('Brakuje wymaganych danych lub login jest zbyt krótki (minimum ' . MIN_SIZE . ' znaków).');
}
?>
