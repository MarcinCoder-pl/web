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

    public function getPostBySlugAndLang($slug, $lang) {
        if (!defined('GET_POST_BY_SLUG_AND_LANG')) {
            throw new Exception("Zapytanie GET_POST_BY_SLUG_AND_LANG nie zostało zdefiniowane.");
        }

        $stmt = $this->conn->prepare(GET_POST_BY_SLUG_AND_LANG);
        $stmt->bind_param("ss", $slug, $lang);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result;
    }

    public function close() {
        $this->conn->close();
    }
}
