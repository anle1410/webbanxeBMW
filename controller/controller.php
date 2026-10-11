<?php
// Main Controller

// Base controller with common functions
class Controller {
    protected $db;
    protected $conn;
    
    public function __construct() {
        global $db;
        $this->db = $db;
        $this->conn = $db->getConnection();
    }

    // Redirect to URL
    protected function redirect($url) {
        header("Location: $url");
        exit();
    }

    // Check if user is logged in
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    // Get current user
    protected function getCurrentUser() {
        if ($this->isLoggedIn()) {
            require_once 'model/user_data.php';
            $user_model = new User();
            return $user_model->getById($_SESSION['user_id']);
        }
        return null;
    }

    // Check if user is admin
    protected function isAdmin() {
        $user = $this->getCurrentUser();
        return $user && $user['role'] == 'admin';
    }

    // Require login
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('index.php?page=login');
        }
    }

    // Require admin
    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('index.php');
        }
    }

    // Get session ID
    protected function getSessionId() {
        if (!isset($_SESSION['session_id'])) {
            $_SESSION['session_id'] = session_id();
        }
        return $_SESSION['session_id'];
    }

    // Generate CSRF token
    protected function generateCSRF() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Verify CSRF token
    protected function verifyCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Show alert message
    protected function showAlert($message, $type = 'info') {
        $_SESSION['alert'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    // Get alert
    protected function getAlert() {
        if (isset($_SESSION['alert'])) {
            $alert = $_SESSION['alert'];
            unset($_SESSION['alert']);
            return $alert;
        }
        return null;
    }

    // Format price
    protected function formatPrice($price) {
        return number_format($price, 0, ',', '.') . ' ₫';
    }

    // Format date
    protected function formatDate($date, $format = 'd/m/Y') {
        return date($format, strtotime($date));
    }

    // Sanitize input
    protected function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
            return $data;
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    // Validate email
    protected function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Validate phone
    protected function isValidPhone($phone) {
        return preg_match('/^[0-9]{10,11}$/', $phone);
    }

    // Paginate
    protected function paginate($total, $per_page, $page) {
        $total_pages = ceil($total / $per_page);
        $offset = ($page - 1) * $per_page;
        
        return [
            'total' => $total,
            'per_page' => $per_page,
            'page' => $page,
            'total_pages' => $total_pages,
            'offset' => $offset
        ];
    }

    // JSON response
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    // File upload
    protected function uploadFile($file, $destination) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $file['tmp_name'];
            $name = basename($file['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            // Allowed extensions
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (!in_array($ext, $allowed)) {
                return ['success' => false, 'message' => 'File type not allowed'];
            }
            
            // Generate unique filename
            $new_name = time() . '_' . $name;
            $target = $destination . '/' . $new_name;
            
            if (move_uploaded_file($tmp_name, $target)) {
                return ['success' => true, 'filename' => $new_name];
            }
        }
        
        return ['success' => false, 'message' => 'Upload failed'];
    }
}

// Initialize
$controller = new Controller();