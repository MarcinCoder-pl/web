<?php
namespace Tools_Network;


class SqlQueryExecutor
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getUserByEmail(string $email): ?array
    {
        $query = "SELECT * FROM users WHERE email = ?";
        $result = $this->db->prepareAndExecute($query, [$email]);
        return $result ? $result->fetch_assoc() : null;
    }

    public function getUserById(int $id): ?array
    {
        $query = "SELECT * FROM users WHERE id = ?";
        $result = $this->db->prepareAndExecute($query, [$id]);
        return $result ? $result->fetch_assoc() : null;
    }

    public function insertUser(string $username, string $email, string $passwordHash): int
    {
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $this->db->prepareAndExecute($query, [$username, $email, $passwordHash]);
        return $this->db->getConnection()->insert_id;
    }

    public function deleteUserById(int $id): int
    {
        $query = "DELETE FROM users WHERE id = ?";
        $this->db->prepareAndExecute($query, [$id]);
        return $this->db->getConnection()->affected_rows;
    }

    public function updateUserEmail(int $id, string $newEmail): int
    {
        $query = "UPDATE users SET email = ? WHERE id = ?";
        $this->db->prepareAndExecute($query, [$newEmail, $id]);
        return $this->db->getConnection()->affected_rows;
    }
}
