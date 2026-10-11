<?php
// Model: User's Data - Enhanced Version

require_once 'config/database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        global $db;
        $this->conn = $db->getConnection();
    }

    public function register($data) {
        if ($this->emailExists($data['email'])) {
            return ['success' => false, 'error' => 'Email đã được sử dụng!'];
        }

        if ($this->phoneExists($data['phone'])) {
            return ['success' => false, 'error' => 'Số điện thoại đã được sử dụng!'];
        }

        $verification_token = bin2hex(random_bytes(32));
        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO {$this->table} (full_name, email, phone, password_hash, address, role, status, email_verified, verification_token, created_at) 
                VALUES (?, ?, ?, ?, ?, 'customer', 'active', 0, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['full_name'],
            $data['email'],
            $data['phone'],
            $password_hash,
            $data['address'],
            $verification_token
        ]);

        if ($stmt->rowCount() > 0) {
            $user_id = $this->conn->lastInsertId();
            $this->createVerificationRecord($user_id, $verification_token);
            return [
                'success' => true,
                'user_id' => $user_id,
                'verification_token' => $verification_token
            ];
        }

        return ['success' => false, 'error' => 'Đăng ký thất bại!'];
    }

    private function createVerificationRecord($user_id, $token) {
        $sql = "INSERT INTO email_verification (user_id, token, created_at, expires_at) 
                VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 24 HOUR))";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id, $token]);
    }

    public function verifyEmail($token) {
        $sql = "SELECT * FROM email_verification WHERE token = ? AND is_used = 0 AND expires_at > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$token]);
        $verification = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($verification) {
            $sql = "SELECT email FROM {$this->table} WHERE user_id = ?";
            $userStmt = $this->conn->prepare($sql);
            $userStmt->execute([$verification['user_id']]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            $email = $user['email'] ?? null;

            $sql = "UPDATE {$this->table} SET email_verified = 1, verification_token = NULL WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$verification['user_id']]);

            $sql = "UPDATE email_verification SET is_used = 1 WHERE verification_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$verification['verification_id']]);

            if ($email) {
                $this->clearFailedAttempts($email);
            }

            return true;
        }

        return false;
    }

    public function resendVerification($email) {
        $user = $this->getByEmail($email);

        if (!$user) {
            return ['success' => false, 'error' => 'Email không tồn tại!'];
        }

        if ($user['email_verified'] == 1) {
            return ['success' => false, 'error' => 'Email đã được xác minh!'];
        }

        $new_token = bin2hex(random_bytes(32));
        $sql = "UPDATE {$this->table} SET verification_token = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$new_token, $user['user_id']]);
        $this->createVerificationRecord($user['user_id'], $new_token);

        return [
            'success' => true,
            'verification_token' => $new_token,
            'email' => $email
        ];
    }

    public function login($email, $password) {
        if ($this->isAccountLocked($email)) {
            return ['success' => false, 'error' => 'Tài khoản bị khóa tạm thời. Vui lòng thử lại sau!'];
        }

        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['email_verified'] != 1) {
                $this->recordFailedAttempt($email);
                return [
                    'success' => false,
                    'error' => 'Email chưa được xác minh! Vui lòng xác minh email trước khi đăng nhập.',
                    'needs_verification' => true,
                    'email' => $email
                ];
            }

            if ($user['status'] != 'active') {
                return ['success' => false, 'error' => 'Tài khoản bị vô hiệu hóa!'];
            }

            $this->clearFailedAttempts($email);
            return ['success' => true, 'user' => $user];
        }

        $this->recordFailedAttempt($email);
        return ['success' => false, 'error' => 'Email hoặc mật khẩu không đúng!'];
    }

    private function isAccountLocked($email) {
        $sql = "SELECT * FROM login_attempts WHERE email = ? AND locked_until > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function recordFailedAttempt($email) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $sql = "SELECT * FROM login_attempts WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        $attempt = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($attempt) {
            $new_attempts = $attempt['attempts'] + 1;
            if ($new_attempts >= 5) {
                $sql = "UPDATE login_attempts SET attempts = ?, last_attempt = NOW(), locked_until = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE email = ?";
            } else {
                $sql = "UPDATE login_attempts SET attempts = ?, last_attempt = NOW(), locked_until = NULL WHERE email = ?";
            }
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$new_attempts, $email]);
        } else {
            $sql = "INSERT INTO login_attempts (email, ip_address, attempts, last_attempt) VALUES (?, ?, 1, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$email, $ip]);
        }
    }

    private function clearFailedAttempts($email) {
        $sql = "DELETE FROM login_attempts WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function update($id, $data) {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }

        $sql = "UPDATE {$this->table} SET " . implode(", ", $fields) . ", updated_at = NOW() WHERE user_id = ?";
        $params[] = $id;
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function changePassword($id, $old_password, $new_password) {
        $user = $this->getById($id);
        if (!$user || !password_verify($old_password, $user['password_hash'])) {
            return false;
        }

        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE {$this->table} SET password_hash = ?, updated_at = NOW() WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$new_hash, $id]);
    }

    public function getAll($role = null, $status = null) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count($role = null) {
        if ($role) {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE role = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$role]);
        } else {
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE {$this->table} SET status = ?, updated_at = NOW() WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function emailExists($email, $exclude_id = null) {
        $sql = "SELECT user_id FROM {$this->table} WHERE email = ?";
        $params = [$email];

        if ($exclude_id) {
            $sql .= " AND user_id != ?";
            $params[] = $exclude_id;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function phoneExists($phone, $exclude_id = null) {
        $sql = "SELECT user_id FROM {$this->table} WHERE phone = ?";
        $params = [$phone];

        if ($exclude_id) {
            $sql .= " AND user_id != ?";
            $params[] = $exclude_id;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Initialize
$user = new User();
// Initialize
$user = new User();