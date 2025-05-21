<?php
// Sprawdzenie, czy plik jest dozwolony do uruchomienia
if (!defined('ACCESS')) {
    die('Brak dostępu.'); // Zakończ wykonywanie, jeśli stała ACCESS nie jest zdefiniowana
}

class Database 
{
    private $connection; // Zmienna przechowująca połączenie z bazą danych

    public function __construct($host, $username, $password, $dbname) 
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Włącz raportowanie błędów jako wyjątki

        try {
            $this->connection = new mysqli($host, $username, $password, $dbname); // Nawiązanie połączenia z bazą danych
            $this->connection->set_charset("utf8mb4"); // Ustawienie zestawu znaków na utf8mb4
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Błąd połączenia z bazą danych: " . $e->getMessage()); // Obsługa błędu połączenia
        }
    }

    public function setCharset($charset) 
    {
        try {
            $this->connection->set_charset($charset); // Ustawienie niestandardowego zestawu znaków
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Błąd ustawiania zestawu znaków: " . $e->getMessage()); // Obsługa błędu ustawienia
        }
    }

    public function prepareAndExecute($query, $params = []) 
    {
        try {
            $stmt = $this->connection->prepare($query); // Przygotowanie zapytania SQL

            if (!empty($params)) {
                $types = $this->getParamTypes($params); // Ustalenie typów parametrów
                $stmt->bind_param($types, ...$params); // Przypisanie parametrów do zapytania
            }

            $stmt->execute(); // Wykonanie zapytania
            $result = $stmt->get_result(); // Pobranie wyniku (jeśli istnieje)

            $output = $result ?: true; // Zwraca wynik zapytania SELECT lub true dla INSERT/UPDATE/DELETE

            $stmt->close(); // Zamknięcie zapytania

            return $output; // Zwrócenie danych
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Błąd wykonania zapytania: " . $e->getMessage()); // Obsługa błędu wykonania
        }
    }

    private function getParamTypes($params) 
    {
        $types = ''; // Łańcuch na typy parametrów
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i'; // Liczba całkowita
            } elseif (is_float($param)) {
                $types .= 'd'; // Liczba zmiennoprzecinkowa
            } else {
                $types .= 's'; // Tekst (string)
            }
        }
        return $types; // Zwrócenie łańcucha typów
    }
    
public function isLoginTaken($username): bool
{
    $query = "SELECT COUNT(*) as count FROM uzytkownicy WHERE login = ?";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0;
}
    public function getConnection()
    {
        return $this->connection;
    }
    public function __destruct() 
    {
        if ($this->connection) {
            $this->connection->close(); // Zamknięcie połączenia z bazą danych przy niszczeniu obiektu
        }
    }
}
