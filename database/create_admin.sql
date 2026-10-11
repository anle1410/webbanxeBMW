-- Script tạo tài khoản admin mới
-- Chạy trong phpMyAdmin hoặc MySQL

-- Thêm tài khoản admin mới vào bảng users
INSERT INTO users (full_name, email, phone, password_hash, address, role, status) 
VALUES (
    'Test Admin',
    'admin@bmw.com.vn',
    '0868264437',
    '0192023a7bbd73250516f069df18b500',
    '7 bùi thế mỹ,tân bình, hcm',
    'admin',
    'active'
);

-- Kiểm tra xem admin đã được tạo chưa
SELECT user_id, full_name, email, role, status FROM users WHERE role = 'admin';