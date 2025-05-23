<?php
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

class MessageBox 
{
    private $db; // obiekt klasy Database

    public function __construct(Database $database) 
    {
        $this->db = $database;
    }

    /**
     * Pobiera wszystkie wiadomości dla zadanego użytkownika (odbiorcy)
     * Zwraca tablicę asocjacyjną z danymi wiadomości
     */
    public function getMessagesForUser(string $username): array
    {
        $query = "SELECT id, sender, subject, content, is_read, created_at 
                  FROM messages 
                  WHERE receiver = ? 
                  ORDER BY created_at DESC";

        $result = $this->db->prepareAndExecute($query, [$username]);

        $messages = [];
        if ($result !== true && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }
            $result->free();
        }
        return $messages;
    }

    /**
     * Pobiera pojedynczą wiadomość po id i użytkowniku (odbiorcy)
     * Zabezpiecza, aby użytkownik widział tylko swoje wiadomości
     */
    public function getMessageByIdForUser(int $messageId, string $username): ?array
    {
        $query = "SELECT id, sender, subject, content, is_read, created_at 
                  FROM messages 
                  WHERE id = ? AND receiver = ?";

        $result = $this->db->prepareAndExecute($query, [$messageId, $username]);

        if ($result !== true && $result->num_rows === 1) {
            return $result->fetch_assoc();
        }

        return null;
    }

    /**
     * Oznacza wiadomość jako przeczytaną
     */
    public function markAsRead(int $messageId, string $username): bool
    {
        $query = "UPDATE messages SET is_read = 1 WHERE id = ? AND receiver = ?";
        $result = $this->db->prepareAndExecute($query, [$messageId, $username]);
        return $result === true;
    }

    /**
     * Usuwa wiadomość użytkownika (odbiorcy)
     */
    public function deleteMessage(int $messageId, string $username): bool
    {
        $query = "DELETE FROM messages WHERE id = ? AND receiver = ?";
        $result = $this->db->prepareAndExecute($query, [$messageId, $username]);
        return $result === true;
    }
}
