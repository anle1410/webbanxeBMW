<?php
// Model: Order's Data

require_once 'config/database.php';

class Order {
    private $conn;
    private $table = 'orders';
    private $table_items = 'order_items';

    public function __construct() {
        global $db;
        $this->conn = $db->getConnection();
    }

    private function generateOrderNumber() {
        return 'BMW' . date('Ymd') . rand(1000, 9999);
    }

    public function create($data, $items) {
        $order_number = $this->generateOrderNumber();
        $sql = "INSERT INTO {$this->table} 
                (order_number, user_id, customer_name, customer_phone, customer_email, 
                 shipping_address, order_notes, payment_method, payment_status, 
                 subtotal, tax_amount, shipping_fee, discount_amount, total_amount, order_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $order_number,
            $data['user_id'],
            $data['customer_name'],
            $data['customer_phone'],
            $data['customer_email'],
            $data['shipping_address'],
            $data['order_notes'],
            $data['payment_method'],
            $data['payment_status'],
            $data['subtotal'],
            $data['tax_amount'],
            $data['shipping_fee'],
            $data['discount_amount'],
            $data['total_amount']
        ]);

        if ($stmt->rowCount() === 0) {
            return false;
        }

        $order_id = $this->conn->lastInsertId();
        foreach ($items as $item) {
            $sql = "INSERT INTO {$this->table_items} 
                    (order_id, vehicle_id, quantity, unit_price, subtotal) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $order_id,
                $item['vehicle_id'],
                $item['quantity'],
                $item['unit_price'],
                $item['subtotal']
            ]);
        }

        $this->addStatusHistory($order_id, 'pending', 'Đơn hàng mới được tạo', $data['user_id'] ?? null);
        return $order_number;
    }

    public function getById($id) {
        $sql = "SELECT o.*, u.full_name, u.email 
                FROM {$this->table} o 
                LEFT JOIN users u ON o.user_id = u.user_id 
                WHERE o.order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByNumber($order_number) {
        $sql = "SELECT o.*, u.full_name, u.email 
                FROM {$this->table} o 
                LEFT JOIN users u ON o.user_id = u.user_id 
                WHERE o.order_number = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$order_number]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByUser($user_id, $limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItems($order_id) {
        $sql = "SELECT oi.*, v.vehicle_name, v.vehicle_code,
                (SELECT image_url FROM vehicle_images WHERE vehicle_id = v.vehicle_id AND is_primary = 1 LIMIT 1) as image
                FROM {$this->table_items} oi
                JOIN vehicles v ON oi.vehicle_id = v.vehicle_id
                WHERE oi.order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll($status = null, $limit = null, $offset = 0) {
        $sql = "SELECT o.*, u.full_name, u.email, u.phone 
                FROM {$this->table} o 
                LEFT JOIN users u ON o.user_id = u.user_id";
        $params = [];

        if ($status) {
            $sql .= " WHERE o.order_status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY o.created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($order_id, $status) {
        $sql = "UPDATE {$this->table} SET order_status = ? WHERE order_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $order_id]);
    }

    public function updatePaymentStatus($order_id, $status) {
        $sql = "UPDATE {$this->table} SET payment_status = ? WHERE order_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $order_id]);
    }

    public function addStatusHistory($order_id, $status, $notes = null, $updated_by = null) {
        $sql = "INSERT INTO order_status_history (order_id, status, notes, updated_by) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$order_id, $status, $notes, $updated_by]);
    }

    public function count($status = null) {
        if ($status) {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE order_status = ?";
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

    public function getRevenue($from = null, $to = null) {
        $sql = "SELECT SUM(total_amount) as revenue, COUNT(*) as total_orders 
                FROM {$this->table} 
                WHERE order_status = 'completed' AND payment_status = 'paid'";
        $params = [];

        if ($from && $to) {
            $sql .= " AND created_at BETWEEN ? AND ?";
            $params[] = $from;
            $params[] = $to;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['revenue' => 0, 'total_orders' => 0];
    }

    public function getRecent($limit = 10) {
        $sql = "SELECT o.*, u.full_name 
                FROM {$this->table} o 
                LEFT JOIN users u ON o.user_id = u.user_id 
                ORDER BY o.created_at DESC 
                LIMIT " . intval($limit);
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Initialize
$order = new Order();