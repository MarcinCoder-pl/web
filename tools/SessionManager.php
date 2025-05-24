<?php

class SessionManager
{
    private $db;
    private $conn;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->conn = $db->getConnection();
    }

    // Tworzy nową sesję i zwraca token sesji
    public function createSession(int $userId, string $ipAddress, string $userAgent, int $durationSeconds = 3600): string
    {
        $sessionToken = bin2hex(random_bytes(32)); // losowy token 64 znaków
        $createdAt = date('Y-m-d H:i:s');
        $expiresAt = date('Y-m-d H:i:s', time() + $durationSeconds);

        $sql = "INSERT INTO sessions (user_id, session_token, ip_address, user_agent, created_at, expires_at) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->conn->error);
        }

        $stmt->bind_param('isssss', $userId, $sessionToken, $ipAddress, $userAgent, $createdAt, $expiresAt);
        $stmt->execute();
        $stmt->close();

        // Usuwa starsze sesje (zostawia tylko 5 najnowszych)
        $this->deleteOldSessions($userId, 5);

        return $sessionToken;
    }

    // Pobiera sesję po tokenie, jeśli istnieje i nie wygasła
    public function getSession(string $sessionToken): ?array
    {
        $sql = "SELECT * FROM sessions WHERE session_token = ? AND expires_at > NOW() LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->conn->error);
        }

        $stmt->bind_param('s', $sessionToken);
        $stmt->execute();
        $result = $stmt->get_result();
        $session = $result->fetch_assoc();

        $stmt->close();

        return $session ?: null;
    }

    // Usuwa sesję po tokenie
    public function deleteSession(string $sessionToken): bool
    {
        $sql = "DELETE FROM sessions WHERE session_token = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->conn->error);
        }

        $stmt->bind_param('s', $sessionToken);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $affectedRows > 0;
    }

    // Usuwa wszystkie sesje użytkownika (np. przy wylogowaniu globalnym)
    public function deleteSessionsByUser(int $userId): int
    {
        $sql = "DELETE FROM sessions WHERE user_id = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->conn->error);
        }

        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $affectedRows;
    }

    // Odświeża czas wygaśnięcia sesji (np. przy aktywności użytkownika)
    public function refreshSession(string $sessionToken, int $durationSeconds = 3600): bool
    {
        $newExpiresAt = date('Y-m-d H:i:s', time() + $durationSeconds);

        $sql = "UPDATE sessions SET expires_at = ? WHERE session_token = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->conn->error);
        }

        $stmt->bind_param('ss', $newExpiresAt, $sessionToken);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $affectedRows > 0;
    }

    // Usuwa stare sesje użytkownika, zostawiając tylko najnowsze (domyślnie 5)
    public function deleteOldSessions(int $userId, int $limit = 5): void
    {
        $sql = "DELETE FROM sessions 
                WHERE user_id = ? 
                AND id NOT IN (
                    SELECT id FROM (
                        SELECT id FROM sessions WHERE user_id = ? ORDER BY created_at DESC LIMIT ?
                    ) AS recent_sessions
                )";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->conn->error);
        }

        $stmt->bind_param('iii', $userId, $userId, $limit);
        $stmt->execute();
        $stmt->close();
    }
}
