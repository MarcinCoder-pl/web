<?php
// Sprawdzenie dostępu
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

class LoginAttemptStats
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getDb(): Database
    {
        return $this->db;
    }

    public function countAllAttempts(): int
    {
        $result = $this->db->prepareAndExecute("SELECT COUNT(*) AS total FROM login_attempts");
        return (int)($result->fetch_assoc()['total'] ?? 0);
    }

    public function countSuccessfulAttempts(): int
    {
        $result = $this->db->prepareAndExecute("SELECT COUNT(*) AS total FROM login_attempts WHERE success = 1");
        return (int)($result->fetch_assoc()['total'] ?? 0);
    }

    public function countFailedAttempts(): int
    {
        $result = $this->db->prepareAndExecute("SELECT COUNT(*) AS total FROM login_attempts WHERE success = 0");
        return (int)($result->fetch_assoc()['total'] ?? 0);
    }

    public function countAttemptsByIP(string $ip): int
    {
        $result = $this->db->prepareAndExecute("SELECT COUNT(*) AS total FROM login_attempts WHERE ip_address = ?", [$ip]);
        return (int)($result->fetch_assoc()['total'] ?? 0);
    }

    public function countAttemptsByUsername(string $username): int
    {
        $result = $this->db->prepareAndExecute("SELECT COUNT(*) AS total FROM login_attempts WHERE username = ?", [$username]);
        return (int)($result->fetch_assoc()['total'] ?? 0);
    }

    public function countFailedAttemptsByUsername(string $username): int
    {
        $result = $this->db->prepareAndExecute("SELECT COUNT(*) AS total FROM login_attempts WHERE username = ? AND success = 0", [$username]);
        return (int)($result->fetch_assoc()['total'] ?? 0);
    }

    public function countFailedAttemptsByIP(string $ip): int
    {
        $result = $this->db->prepareAndExecute("SELECT COUNT(*) AS total FROM login_attempts WHERE ip_address = ? AND success = 0", [$ip]);
        return (int)($result->fetch_assoc()['total'] ?? 0);
    }
}
