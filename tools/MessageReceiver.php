<?php
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

class MessageReceiver
{
    private $db;
    private $conn;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->conn = $db->getConnection();
    }

    /**
     * Pobiera listę wiadomości odebranych przez użytkownika
     * @param int $userId
     * @param int $limit - ile wiadomości pobrać (domyślnie 20)
     * @param int $offset - przesunięcie dla paginacji
     * @return array lista wiadomości
     * @throws Exception
     */
    public function getReceivedMessages(int $userId, int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT m.id, m.sender_id, u.username AS sender_username, m.subject, m.body, m.is_read, m.created_at
                FROM messages m
                JOIN users u ON m.sender_id = u.id
                WHERE m.receiver_id = ?
                ORDER BY m.created_at DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->conn->error);
        }

        $stmt->bind_param("iii", $userId, $limit, $offset);
        $stmt->execute();

        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $messages;
    }

    /**
     * Oznacza wiadomość jako przeczytaną
     * @param int $messageId
     * @param int $userId - właściciel wiadomości (odbiorca)
     * @return bool
     * @throws Exception
     */
    public function markAsRead(int $messageId, int $userId): bool
    {
        $sql = "UPDATE messages SET is_read = 1 WHERE id = ? AND receiver_id = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->conn->error);
        }

        $stmt->bind_param("ii", $messageId, $userId);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        return $affected > 0;
    }

    /**
     * Pobiera pojedynczą wiadomość po id i userId (odbiorca)
     * @param int $messageId
     * @param int $userId
     * @return array|null
     * @throws Exception
     */
    public function getMessageById(int $messageId, int $userId): ?array
    {
        $sql = "SELECT m.id, m.sender_id, u.username AS sender_username, m.subject, m.body, m.is_read, m.created_at
                FROM messages m
                JOIN users u ON m.sender_id = u.id
                WHERE m.id = ? AND m.receiver_id = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->conn->error);
        }

        $stmt->bind_param("ii", $messageId, $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $message = $result->fetch_assoc();

        $stmt->close();

        return $message ?: null;
    }
}
