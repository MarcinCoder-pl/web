<?php
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

class AuditLogger
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function log(?int $userId, string $action, string $details = ''): void
    {
        $query = "INSERT INTO audit_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())";
        $this->db->prepareAndExecute($query, [$userId, $action, $details]);
    }
}
