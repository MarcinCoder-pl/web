<?php
// Sprawdzenie, czy plik jest dozwolony do uruchomienia
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

class Database 
{
    private $connection;

    public function __construct($host, $username, $password, $dbname) 
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->connection = new mysqli($host, $username, $password, $dbname);
            $this->connection->set_charset("utf8mb4");
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Błąd połączenia z bazą danych: " . $e->getMessage());
        }
    }

    public function setCharset($charset) 
    {
        try {
            $this->connection->set_charset($charset);
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Błąd ustawiania zestawu znaków: " . $e->getMessage());
        }
    }

    public function prepareAndExecute($query, $params = []) 
    {
        try {
            $stmt = $this->connection->prepare($query);

            if (!empty($params)) {
                $types = $this->getParamTypes($params);
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $output = $result ?: true;

            $stmt->close();

            return $output;
        } catch (mysqli_sql_exception $e) {
            throw new Exception("Błąd wykonania zapytania: " . $e->getMessage());
        }
    }

    private function getParamTypes($params) 
    {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        return $types;
    }

    public function isLoginTaken(string $username): bool
    {
        $query = "SELECT COUNT(*) as count FROM users WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->connection->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }

    public function isEmailTaken(string $email): bool
    {
        $query = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->connection->error);
        }
        $stmt->bind_param("s", $email);
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
            $this->connection->close();
        }
    }
}
