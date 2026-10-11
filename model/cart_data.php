<?php
// Model: Dữ liệu giỏ hàng
// Class Cart chịu trách nhiệm tất cả thao tác CRUD với bảng cart
// - Lấy sản phẩm trong giỏ hàng
// - Thêm sản phẩm vào giỏ hàng
// - Cập nhật số lượng
// - Xóa sản phẩm
// - Tính tổng giá trị giỏ hàng

require_once 'config/database.php';

class Cart {
    private $conn;
    private $table = 'cart';
    
    public function __construct() {
        global $db;
        $this->conn = $db->getConnection();
    }

    // Lấy danh sách sản phẩm trong giỏ hàng
    // Nếu user_id tồn tại thì lấy cả sản phẩm với session_id và user_id (dùng khi người dùng đăng nhập)
    // Nếu không có user_id thì chỉ lấy theo session_id
    public function getItems($session_id, $user_id = null) {
        if ($user_id) {
            $sql = "SELECT c.*, v.vehicle_name, v.vehicle_code, v.price, v.promotional_price, v.status,
                    (SELECT image_url FROM vehicle_images WHERE vehicle_id = v.vehicle_id AND is_primary = 1 LIMIT 1) as image
                    FROM {$this->table} c
                    JOIN vehicles v ON c.vehicle_id = v.vehicle_id
                    WHERE c.session_id = ? OR c.user_id = ?
                    ORDER BY c.created_at DESC";
            $params = [$session_id, $user_id];
        } else {
            $sql = "SELECT c.*, v.vehicle_name, v.vehicle_code, v.price, v.promotional_price, v.status,
                    (SELECT image_url FROM vehicle_images WHERE vehicle_id = v.vehicle_id AND is_primary = 1 LIMIT 1) as image
                    FROM {$this->table} c
                    JOIN vehicles v ON c.vehicle_id = v.vehicle_id
                    WHERE c.session_id = ?
                    ORDER BY c.created_at DESC";
            $params = [$session_id];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm vào giỏ hàng
    // Nếu sản phẩm đã tồn tại trong giỏ hàng thì cộng dồn số lượng
    public function addItem($vehicle_id, $quantity = 1, $session_id = null, $user_id = null) {
        if ($user_id) {
            $sql = "SELECT * FROM {$this->table} WHERE vehicle_id = ? AND (session_id = ? OR user_id = ?)";
            $params = [$vehicle_id, $session_id, $user_id];
        } else {
            $sql = "SELECT * FROM {$this->table} WHERE vehicle_id = ? AND session_id = ?";
            $params = [$vehicle_id, $session_id];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // Nếu đã có trong giỏ, cập nhật lại số lượng cũ + số lượng mới
            $new_quantity = $item['quantity'] + $quantity;
            $sql = "UPDATE {$this->table} SET quantity = ? WHERE cart_id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$new_quantity, $item['cart_id']]);
        }

        $sql = "INSERT INTO {$this->table} (vehicle_id, quantity, session_id, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$vehicle_id, $quantity, $session_id, $user_id]);
    }

    // Cập nhật số lượng cho 1 item trong giỏ hàng theo cart_id
    public function updateQuantity($cart_id, $quantity) {
        $sql = "UPDATE {$this->table} SET quantity = ? WHERE cart_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$quantity, $cart_id]);
    }

    // Xóa 1 sản phẩm khỏi giỏ hàng theo cart_id
    public function removeItem($cart_id) {
        $sql = "DELETE FROM {$this->table} WHERE cart_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$cart_id]);
    }

    // Clear cart
    public function clearCart($session_id, $user_id = null) {
        if ($user_id) {
            $sql = "DELETE FROM {$this->table} WHERE session_id = ? OR user_id = ?";
            $params = [$session_id, $user_id];
        } else {
            $sql = "DELETE FROM {$this->table} WHERE session_id = ?";
            $params = [$session_id];
        }

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Lấy tổng số lượng sản phẩm trong giỏ hàng
    // Dùng để hiển thị biểu tượng số lượng giỏ hàng trên header
    public function getCount($session_id, $user_id = null) {
        if ($user_id) {
            $sql = "SELECT COALESCE(SUM(quantity), 0) as total FROM {$this->table} WHERE session_id = ? OR user_id = ?";
            $params = [$session_id, $user_id];
        } else {
            $sql = "SELECT COALESCE(SUM(quantity), 0) as total FROM {$this->table} WHERE session_id = ?";
            $params = [$session_id];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'] ?? 0;
    }

    // Tính tổng tiền giỏ hàng
    // subtotal: tổng trước thuế
    // tax: thuế 10%
    // total: tổng cuối cùng
    public function getTotal($session_id, $user_id = null) {
        $items = $this->getItems($session_id, $user_id);
        $subtotal = 0;

        foreach ($items as $item) {
            $price = $item['promotional_price'] ? $item['promotional_price'] : $item['price'];
            $subtotal += $price * $item['quantity'];
        }

        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total
        ];
    }

    // Cập nhật user_id cho các item đang có trong giỏ hàng thuộc session hiện tại
    // Dùng khi người dùng đăng nhập sau khi đã thêm sản phẩm bằng khách (session_id)
    public function updateUserId($session_id, $user_id) {
        $sql = "UPDATE {$this->table} SET user_id = ? WHERE session_id = ? AND user_id IS NULL";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$user_id, $session_id]);
    }
}

// Initialize
$cart = new Cart();