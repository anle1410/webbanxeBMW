-- Upgrade Database for Enhanced User System
-- Run this file in phpMyAdmin

-- Add new columns to users table if not exists
ALTER TABLE users ADD COLUMN IF NOT EXISTS email_verified TINYINT(1) DEFAULT 0;
ALTER TABLE users ADD COLUMN IF NOT EXISTS verification_token VARCHAR(255) DEFAULT NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT NULL;

-- Update existing admin account to verified
UPDATE users SET email_verified = 1 WHERE role = 'admin';

-- Create email_verification table
CREATE TABLE IF NOT EXISTS email_verification (
    verification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME DEFAULT (CURRENT_TIMESTAMP + INTERVAL 24 HOUR),
    is_used TINYINT(1) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Create login_attempts table to track failed login attempts
CREATE TABLE IF NOT EXISTS login_attempts (
    attempt_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempts INT DEFAULT 1,
    last_attempt DATETIME DEFAULT CURRENT_TIMESTAMP,
    locked_until DATETIME DEFAULT NULL
);

-- Insert sample verified customer
INSERT INTO users (full_name, email, password, phone, address, role, email_verified, created_at) VALUES
('Nguyen Van A', 'nguyenvana@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0912345678', '123 Đường ABC, Quận XYZ, TP.HCM', 'customer', 1, NOW());

SELECT 'Database upgraded successfully!' as message;