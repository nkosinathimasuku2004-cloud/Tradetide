<?php
require_once '../config/database.php';

class Message {
    private $conn;
    private $table = 'messages';

    public $id;
    public $sender_id;
    public $receiver_id;
    public $barter_id;
    public $subject;
    public $message;
    public $is_read;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Create message
    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  (sender_id, receiver_id, barter_id, subject, message)
                  VALUES (:sender_id, :receiver_id, :barter_id, :subject, :message)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':sender_id', $this->sender_id);
        $stmt->bindParam(':receiver_id', $this->receiver_id);
        $stmt->bindParam(':barter_id', $this->barter_id);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':message', $this->message);

        return $stmt->execute();
    }

    // Read user's messages (inbox)
    public function readInbox($user_id, $limit = 20, $offset = 0) {
        $query = "SELECT m.*,
                         u.first_name as sender_first_name,
                         u.last_name as sender_last_name,
                         u.profile_image as sender_image,
                         b.title as barter_title
                  FROM " . $this->table . " m
                  LEFT JOIN users u ON m.sender_id = u.id
                  LEFT JOIN barters b ON m.barter_id = b.id
                  WHERE m.receiver_id = :user_id
                  ORDER BY m.created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    // Read user's sent messages
    public function readSent($user_id, $limit = 20, $offset = 0) {
        $query = "SELECT m.*,
                         u.first_name as receiver_first_name,
                         u.last_name as receiver_last_name,
                         u.profile_image as receiver_image,
                         b.title as barter_title
                  FROM " . $this->table . " m
                  LEFT JOIN users u ON m.receiver_id = u.id
                  LEFT JOIN barters b ON m.barter_id = b.id
                  WHERE m.sender_id = :user_id
                  ORDER BY m.created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    // Read conversation between two users
    public function readConversation($user1_id, $user2_id, $barter_id = null) {
        $query = "SELECT m.*,
                         sender.first_name as sender_first_name,
                         sender.last_name as sender_last_name,
                         sender.profile_image as sender_image,
                         receiver.first_name as receiver_first_name,
                         receiver.last_name as receiver_last_name,
                         receiver.profile_image as receiver_image,
                         b.title as barter_title
                  FROM " . $this->table . " m
                  LEFT JOIN users sender ON m.sender_id = sender.id
                  LEFT JOIN users receiver ON m.receiver_id = receiver.id
                  LEFT JOIN barters b ON m.barter_id = b.id
                  WHERE ((m.sender_id = :user1_id AND m.receiver_id = :user2_id)
                     OR (m.sender_id = :user2_id AND m.receiver_id = :user1_id))";

        if ($barter_id) {
            $query .= " AND m.barter_id = :barter_id";
        }

        $query .= " ORDER BY m.created_at ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user1_id', $user1_id);
        $stmt->bindParam(':user2_id', $user2_id);

        if ($barter_id) {
            $stmt->bindParam(':barter_id', $barter_id);
        }

        $stmt->execute();
        return $stmt;
    }

    // Mark message as read
    public function markAsRead($id, $user_id) {
        $query = "UPDATE " . $this->table . "
                  SET is_read = 1
                  WHERE id = :id AND receiver_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);

        return $stmt->execute();
    }

    // Count unread messages
    public function countUnread($user_id) {
        $query = "SELECT COUNT(*) as unread_count
                  FROM " . $this->table . "
                  WHERE receiver_id = :user_id AND is_read = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['unread_count'];
    }

    // Delete message
    public function delete($id, $user_id) {
        $query = "DELETE FROM " . $this->table . "
                  WHERE id = :id AND (sender_id = :user_id OR receiver_id = :user_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);

        return $stmt->execute();
    }
}
?>
