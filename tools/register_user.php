<?php
// Rozpoczynamy sesję
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Start skryptu<br>";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "Sesja rozpoczęta<br>";
}

define('ACCESS', true);

require_once '../config/db_config.php'; // Połączenie z bazą danych
require_once 'validate_form_fields.php';
require_once 'csrf_token.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Odebrano POST<br>";

    $token = $_POST['csrf_token'] ?? '';
    if (validateCsrfToken($token)) {
        echo "Token CSRF prawidłowy<br>";

        if (isset($_POST['username'], $_POST['password'], $_POST['password_confirm'])) {
            echo "Odebrano dane użytkownika<br>";

            if (
                strlen($_POST['username']) >= MIN_LENGTH_REGISTER &&
                strlen($_POST['username']) <= MAX_LENGTH_REGISTER &&
                strlen($_POST['password']) >= MIN_LENGTH_REGISTER
            ) {
                echo "Dane mają odpowiednią długość<br>";

                if ($_POST['password'] === $_POST['password_confirm']) {
                    echo "Hasła się zgadzają<br>";

                    $hashed_pass = haszujHaslo($_POST['password']);
                    $login = convert_to_utf8($_POST['username']);

                    if (isAlphanumeric($login)) {
                        echo "Login alfanumeryczny<br>";

                        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

                        if ($conn->connect_error) {
                            die("Połączenie nie powiodło się: " . $conn->connect_error);
                        }

                        echo "Połączono z bazą danych<br>";

                        if (!isLoginTaken($conn, $login)) {
                            echo "Login wolny<br>";

                            $stmt = $conn->prepare(ADD_ACC);
                            $stmt->bind_param("ss", $login, $hashed_pass);

                            if ($stmt->execute()) {
                                echo "Rejestracja zakończona sukcesem<br>";
                                $_SESSION['username'] = $login;

                                setcookie('username', $_SESSION['username'], time() + 3600, "/", false, true);
                                echo "Przekierowanie na dashboard...<br>";
                                header('Location: /index.php?strona=dashboard');
                                exit;
                            } else {
                                echo "Błąd przy wykonaniu zapytania SQL<br>";
                                header('Location: ../index.php?strona=home');
                                exit;
                            }

                            $stmt->close();
                            $conn->close();
                        } else {
                            echo "Login jest już zajęty<br>";
                        }
                    } else {
                        echo "Login zawiera niedozwolone znaki<br>";
                    }
                } else {
                    echo "Hasła nie są zgodne<br>";
                }
            } else {
                echo "Długość loginu lub hasła nie jest odpowiednia<br>";
            }
        } else {
            echo "Nie przesłano wymaganych danych<br>";
        }
    } else {
        echo "Błąd CSRF tokenu<br>";
    }
} else {
    echo "Brak danych POST<br>";
}
