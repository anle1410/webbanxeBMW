<?php
// Model: Test Drive's Data

require_once 'config/database.php';

class TestDrive {
    private $conn;
    private $table = 'test_drive';

    public function __construct() {
        global $db;
        $this->conn = $db->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (user_id, vehicle_id, full_name, phone, email, 
                 desired_date, desired_time, pickup_location, status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['user_id'],
            $data['vehicle_id'],
            $data['full_name'],
            $data['phone'],
            $data['email'],
            $data['desired_date'],
            $data['desired_time'],
            $data['pickup_location']
        ]);

        return $this->conn->lastInsertId() ?: false;
    }

    public function getByUser($user_id) {
        $sql = "SELECT td.*, v.vehicle_name, v.vehicle_code,
                (SELECT image_url FROM vehicle_images WHERE vehicle_id = v.vehicle_id AND is_primary = 1 LIMIT 1) as image
                FROM {$this->table} td
                JOIN vehicles v ON td.vehicle_id = v.vehicle_id
                WHERE td.user_id = ?
                ORDER BY td.desired_date DESC, td.desired_time DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT td.*, v.vehicle_name, v.vehicle_code
                FROM {$this->table} td
                JOIN vehicles v ON td.vehicle_id = v.vehicle_id
                WHERE td.test_drive_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll($status = null, $limit = null, $offset = 0) {
        $sql = "SELECT td.*, v.vehicle_name, v.vehicle_code, u.full_name as customer_name, u.phone as customer_phone
                FROM {$this->table} td
                JOIN vehicles v ON td.vehicle_id = v.vehicle_id
                LEFT JOIN users u ON td.user_id = u.user_id";
        $params = [];

        if ($status) {
            $sql .= " WHERE td.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY td.desired_date ASC, td.desired_time ASC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status, $notes = null) {
        $sql = "UPDATE {$this->table} SET status = ?";
        $params = [$status];

        if ($notes) {
            $sql .= ", admin_notes = ?";
            $params[] = $notes;
        }

        $sql .= " WHERE test_drive_id = ?";
        $params[] = $id;

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function cancel($id) {
        return $this->updateStatus($id, 'cancelled');
    }

    public function complete($id) {
        return $this->updateStatus($id, 'completed');
    }

    public function confirm($id) {
        return $this->updateStatus($id, 'confirmed');
    }

    public function count($status = null) {
        if ($status) {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$status]);
        } else {
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    public function getAvailableSlots($date, $location = null) {
        $all_slots = [
            '08:00:00', '09:00:00', '10:00:00', '11:00:00',
            '13:00:00', '14:00:00', '15:00:00', '16:00:00', '17:00:00'
        ];

        $sql = "SELECT desired_time FROM {$this->table} 
                WHERE desired_date = ? AND status IN ('pending', 'confirmed')";
        $params = [$date];

        if ($location) {
            $sql .= " AND pickup_location = ?";
            $params[] = $location;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $booked = array_column($rows, 'desired_time');
        return array_diff($all_slots, $booked);
    }
}

// Initialize
$testdrive = new TestDrive();