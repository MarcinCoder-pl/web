<?php

class ErrorMessageProvider
{
    private $db;
    private $lang;
    private $cache = [];

    public function __construct($db, $lang = 'pl')
    {
        $this->db = $db;
        $this->lang = strtolower($lang);
    }

    /**
     * Pobiera komunikat błędu według kodu błędu i opcjonalnego kontekstu.
     * Jeśli nie znajdzie komunikatu w żądanym języku, użyje angielskiego.
     * 
     * @param string $errorCode Kod błędu, np. 'INVALID_CREDENTIALS'
     * @param string|null $context Kontekst błędu, np. 'registration', 'auth' (opcjonalny)
     * @return string Komunikat błędu
     */
    public function getMessage(string $errorCode, ?string $context = null): string
    {
        $cacheKey = $errorCode . '|' . ($context ?? '');
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $conn = $this->db->getConnection();

        // Zapytanie: najpierw znajdź id błędu z error_messages według error_code i opcjonalnie kontekstu
        $sql = "SELECT id FROM error_messages WHERE error_code = ?";
        $params = [$errorCode];

        if ($context !== null) {
            $sql .= " AND context = ?";
            $params[] = $context;
        } else {
            // Jeśli kontekst nie podany, dopuszczamy NULL (lub brak kontekstu)
            $sql .= " AND (context IS NULL OR context = '')";
        }

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return "Internal error [prepare error_messages]";
        }

        // Bind parametry dynamicznie:
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        $stmt->bind_result($errorId);
        if (!$stmt->fetch()) {
            $stmt->close();
            return "Unknown error: $errorCode";
        }
        $stmt->close();

        // Teraz pobierz tłumaczenie z error_translations wg errorId i języka
        $sql2 = "SELECT message FROM error_translations WHERE error_id = ? AND language_code = ? LIMIT 1";
        $stmt2 = $conn->prepare($sql2);
        if (!$stmt2) {
            return "Internal error [prepare error_translations]";
        }
        $stmt2->bind_param("is", $errorId, $this->lang);
        $stmt2->execute();
        $stmt2->bind_result($message);
        if ($stmt2->fetch()) {
            $stmt2->close();
            $this->cache[$cacheKey] = $message;
            return $message;
        }
        $stmt2->close();

        // Jeśli brak tłumaczenia w wybranym języku, spróbuj po angielsku
        if ($this->lang !== 'en') {
            $stmt3 = $conn->prepare($sql2);
            if (!$stmt3) {
                return "Internal error [prepare error_translations fallback]";
            }
            $stmt3->bind_param("is", $errorId, $en = 'en');
            $stmt3->execute();
            $stmt3->bind_result($messageEn);
            if ($stmt3->fetch()) {
                $stmt3->close();
                $this->cache[$cacheKey] = $messageEn;
                return $messageEn;
            }
            $stmt3->close();
        }

        return "Unknown error: $errorCode";
    }
}
