<?php
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}

class MessageSender
{
    private $db;
    private $conn;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->conn = $db->getConnection();
    }

    /**
     * Wysyła wiadomość od użytkownika sender_id do receiver_id
     * @param int $senderId
     * @param int $receiverId
     * @param string $subject
     * @param string $body
     * @return bool true jeśli wysłano poprawnie, false w przeciwnym razie
     * @throws Exception w przypadku błędów bazy danych
     */
    public function sendMessage(int $senderId, int $receiverId, string $subject, string $body): bool
    {
        $subject = trim($subject);
        $body = trim($body);

        if (empty($subject) || empty($body)) {
            throw new Exception("Temat i treść wiadomości nie mogą być puste.");
        }

        $sql = "INSERT INTO messages (sender_id, receiver_id, subject, body) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->conn->error);
        }

        $stmt->bind_param("iiss", $senderId, $receiverId, $subject, $body);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
    public function getUserIdByUsername(string $username): ?int
{
    $stmt = $this->db->getConnection()->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return (int)$row['id'];
    }
    
    return null; // użytkownik nie istnieje
}

}
