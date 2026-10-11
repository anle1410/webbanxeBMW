<?php
// Model: Lưu trữ và truy vấn tin nhắn liên hệ từ khách
require_once 'config/database.php';

class MessageModel {
    private $conn;
    private $table = 'messages';

    public function __construct() {
        global $db;
        $this->conn = $db->getConnection();
    }

    // Tạo tin nhắn mới
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, email, phone, subject, message, is_read, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['name'] ?? '',
            $data['email'] ?? '',
            $data['phone'] ?? '',
            $data['subject'] ?? '',
            $data['message'] ?? ''
        ]);
    }

    // Lấy số tin nhắn chưa đọc
    public function getUnreadCount() {
        try {
            $sql = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE is_read = 0";
            $stmt = $this->conn->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return intval($row['cnt'] ?? 0);
        } catch (PDOException $e) {
            // Nếu bảng chưa tồn tại hoặc lỗi DB khác, trả về 0 tránh Fatal error
            return 0;
        }
    }

    // Lấy danh sách tin nhắn, theo thứ tự mới nhất
    public function getAll($limit = 0) {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
            if ($limit && intval($limit) > 0) {
                $sql .= " LIMIT " . intval($limit);
            }
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Nếu bảng không tồn tại, trả về mảng rỗng
            return [];
        }
    }

    // Đánh dấu tin nhắn đã đọc
    public function markAsRead($id) {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([intval($id)]);
    }

    // Xóa tin nhắn
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([intval($id)]);
    }
}

// Gợi ý cấu trúc bảng SQL:
// CREATE TABLE `messages` (
//   `id` INT AUTO_INCREMENT PRIMARY KEY,
//   `name` VARCHAR(255),
//   `email` VARCHAR(255),
//   `phone` VARCHAR(50),
//   `subject` VARCHAR(255),
//   `message` TEXT,
//   `is_read` TINYINT(1) DEFAULT 0,
//   `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
