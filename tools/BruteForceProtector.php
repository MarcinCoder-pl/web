<?php
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
        $query = "SELECT COUNT(*) AS total FROM login_attempts WHERE ip_address = ? AND success = 0 AND attempt_time > (NOW() - INTERVAL ? SECOND)";
        $result = $this->stats->getDb()->prepareAndExecute($query, [$ip, $this->lockoutTimeSeconds]);
        return (int)($result->fetch_assoc()['total'] ?? 0) >= $this->maxAttemptsPerIP;
    }

    public function isBlockedByUsername(string $username): bool
    {
        $query = "SELECT COUNT(*) AS total FROM login_attempts WHERE email_or_username = ? AND success = 0 AND attempt_time > (NOW() - INTERVAL ? SECOND)";
        $result = $this->stats->getDb()->prepareAndExecute($query, [$username, $this->lockoutTimeSeconds]);
        return (int)($result->fetch_assoc()['total'] ?? 0) >= $this->maxAttemptsPerUsername;
    }

    public function getRemainingBlockTime(string $ip, string $username): int
    {
        $query = "SELECT MIN(attempt_time) AS first_fail FROM login_attempts WHERE (ip_address = ? OR email_or_username = ?) AND success = 0 AND attempt_time > (NOW() - INTERVAL ? SECOND)";
        $result = $this->stats->getDb()->prepareAndExecute($query, [$ip, $username, $this->lockoutTimeSeconds]);

        $row = $result->fetch_assoc();
        if ($row && $row['first_fail']) {
            $firstFail = strtotime($row['first_fail']);
            $remaining = ($firstFail + $this->lockoutTimeSeconds) - time();
            return max(0, $remaining);
        }
        return 0;
    }
}
