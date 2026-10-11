<?php
// Model: Xe's Data

require_once 'config/database.php';

class Xe {
    private $conn;
    private $table = 'vehicles';
    private $table_images = 'vehicle_images';
    private $table_categories = 'categories';

    public function __construct() {
        global $db;
        $this->conn = $db->getConnection();
    }

    // Get all vehicles
    public function getAll($limit = null, $offset = 0, $status = null) {
        $sql = "SELECT v.*, c.category_name, c.category_slug 
                FROM {$this->table} v 
                LEFT JOIN {$this->table_categories} c ON v.category_id = c.category_id 
                WHERE 1=1";
        $params = [];

        if ($status !== null) {
            $sql .= " AND v.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY v.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get vehicle by ID
    public function getById($id) {
        $sql = "SELECT v.*, c.category_name, c.category_slug 
                FROM {$this->table} v 
                LEFT JOIN {$this->table_categories} c ON v.category_id = c.category_id 
                WHERE v.vehicle_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Get vehicles by category
    public function getByCategory($category_id, $limit = null) {
        $sql = "SELECT v.*, c.category_name, c.category_slug 
                FROM {$this->table} v 
                LEFT JOIN {$this->table_categories} c ON v.category_id = c.category_id 
                WHERE v.category_id = ? AND v.status = 'available' 
                ORDER BY v.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get related vehicles (same category)
    public function getRelated($vehicle_id, $category_id, $limit = 4) {
        $sql = "SELECT v.*, c.category_name, c.category_slug 
                FROM {$this->table} v 
                LEFT JOIN {$this->table_categories} c ON v.category_id = c.category_id 
                WHERE v.category_id = ? AND v.vehicle_id != ? AND v.status = 'available' 
                ORDER BY RAND() 
                LIMIT " . intval($limit);

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$category_id, $vehicle_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get featured vehicles
    public function getFeatured($limit = 6) {
        $sql = "SELECT v.*, c.category_name, c.category_slug 
                FROM {$this->table} v 
                LEFT JOIN {$this->table_categories} c ON v.category_id = c.category_id 
                WHERE v.is_featured = 1 AND v.status = 'available' 
                ORDER BY v.created_at DESC 
                LIMIT " . intval($limit);

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search vehicles
    public function search($keyword) {
        $sql = "SELECT v.*, c.category_name, c.category_slug 
                FROM {$this->table} v 
                LEFT JOIN {$this->table_categories} c ON v.category_id = c.category_id 
                WHERE v.vehicle_name LIKE ? AND v.status = 'available' 
                ORDER BY v.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["%{$keyword}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get images for vehicle
    public function getImages($vehicle_id) {
        $sql = "SELECT * FROM {$this->table_images} 
                WHERE vehicle_id = ? 
                ORDER BY display_order ASC, is_primary DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$vehicle_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get primary image
    public function getPrimaryImage($vehicle_id) {
        $sql = "SELECT * FROM {$this->table_images} 
                WHERE vehicle_id = ? AND is_primary = 1 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$vehicle_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Count all vehicles
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

    // Get all vehicles (for admin)
    public function getAllAdmin($status = null) {
        $sql = "SELECT v.*, c.category_name 
                FROM {$this->table} v 
                LEFT JOIN {$this->table_categories} c ON v.category_id = c.category_id";
        $params = [];

        if ($status) {
            $sql .= " WHERE v.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY v.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Initialize
$xe = new Xe();