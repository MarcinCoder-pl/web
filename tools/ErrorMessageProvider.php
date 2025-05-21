<?php

class ErrorMessageProvider
{
    private $db;
    private $lang;
    private $cache = [];

    public function __construct($db, $lang = 'pl')
    {
        $this->db = $db;
        $this->lang = $lang;
    }

    public function getMessage(string $errorCode): string
    {
        // Zwróć z cache, jeśli istnieje
        if (isset($this->cache[$errorCode])) {
            return $this->cache[$errorCode];
        }

        $conn = $this->db->getConnection();

        // Ustal nazwę kolumny językowej
        $column = 'message_' . strtolower($this->lang);
        $fallback = 'message_en';

        // Przygotuj zapytanie SQL
        $sql = "SELECT `$column` AS msg, `$fallback` AS fallback FROM registration_errors WHERE error_code = ? LIMIT 1";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return "Internal error [prepare]";
        }

        $stmt->bind_param("s", $errorCode);
        $stmt->execute();

        $stmt->bind_result($msg, $fallbackMsg);
        if ($stmt->fetch()) {
            $stmt->close();

            // Wybierz odpowiedni komunikat
            $message = $msg ?: $fallbackMsg;
            $this->cache[$errorCode] = $message;
            return $message;
        }

        $stmt->close();
        return "Unknown error: $errorCode";
    }
}

