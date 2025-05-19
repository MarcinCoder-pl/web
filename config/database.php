<?php
// classes/Database.php

class Database {
    private $conn;

    public function __construct($host, $user, $pass, $name) {
        $this->conn = new mysqli($host, $user, $pass, $name);
        if ($this->conn->connect_error) {
            throw new Exception("Błąd połączenia: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }

    /**
     * Uniwersalna metoda do wykonywania zapytań przygotowanych
     * @param string $query - zapytanie SQL z placeholderami (np. SELECT * FROM posts WHERE slug = ? AND lang = ?)
     * @param string $paramTypes - typy parametrów (np. "ss" dla dwóch stringów)
     * @param array $params - wartości do podstawienia
     * @return mysqli_result|false
     */
    public function query($query, $paramTypes = "", $params = []) {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->conn->error);
        }

        if (!empty($paramTypes) && !empty($params)) {
            $stmt->bind_param($paramTypes, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result;
    }

    public function close() {
        $this->conn->close();
    }
}
