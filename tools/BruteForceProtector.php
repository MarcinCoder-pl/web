<?php
// Sprawdzenie dostępu
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

class BruteForceProtector
{
    private LoginAttemptStats $stats;
    private int $maxAttemptsPerIP;
    private int $maxAttemptsPerUsername;
    private int $lockoutTimeSeconds;

    public function __construct(LoginAttemptStats $stats, int $maxAttemptsPerIP = 5, int $maxAttemptsPerUsername = 5, int $lockoutTimeSeconds = 900)
    {
        $this->stats = $stats;
        $this->maxAttemptsPerIP = $maxAttemptsPerIP;
        $this->maxAttemptsPerUsername = $maxAttemptsPerUsername;
        $this->lockoutTimeSeconds = $lockoutTimeSeconds;
    }

    public function isBlocked(string $ip, string $username): bool
    {
        return $this->isBlockedByIP($ip) || $this->isBlockedByUsername($username);
    }

    public function isBlockedByIP(string $ip): bool
    {
        $query = "SELECT COUNT(*) AS total FROM login_attempts WHERE ip_address = ? AND success = 0 AND timestamp > (NOW() - INTERVAL ? SECOND)";
        $result = $this->stats->getDb()->prepareAndExecute($query, [$ip, $this->lockoutTimeSeconds]);
        return (int)($result->fetch_assoc()['total'] ?? 0) >= $this->maxAttemptsPerIP;
    }

    public function isBlockedByUsername(string $username): bool
    {
        $query = "SELECT COUNT(*) AS total FROM login_attempts WHERE username = ? AND success = 0 AND timestamp > (NOW() - INTERVAL ? SECOND)";
        $result = $this->stats->getDb()->prepareAndExecute($query, [$username, $this->lockoutTimeSeconds]);
        return (int)($result->fetch_assoc()['total'] ?? 0) >= $this->maxAttemptsPerUsername;
    }
}
