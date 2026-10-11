<?php
// View: Register - Enhanced with validation
global $register_error, $register_success;
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Đăng ký tài khoản</h3>
                    
                    <?php if(isset($register_error)): ?>
                        <div class="alert alert-danger"><?php echo $register_error; ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($register_success)): ?>
                        <div class="alert alert-success">
                            <h5>Đăng ký thành công!</h5>
                            <p><?php echo $register_success; ?></p>
                            <a href="index.php?page=login" class="btn btn-primary">Đăng nhập</a>
                        </div>
                    <?php else: ?>
                    
                    <form method="POST" id="registerForm" onsubmit="return validateRegisterForm()">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control" 
                                   placeholder="Ví dụ: Phạm Văn A" required>
                            <small class="text-danger" id="full_name_error"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" 
                                   placeholder="Ví dụ: nguyenvana@gmail.com" required>
                            <small class="text-danger" id="email_error"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" id="phone" class="form-control" 
                                   placeholder="Ví dụ: 0912345678" maxlength="10" required>
                            <small class="text-danger" id="phone_error"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                            <textarea name="address" id="address" class="form-control" rows="2" 
                                      placeholder="Ví dụ: 123 Đường ABC, Quận XYZ" required></textarea>
                            <small class="text-danger" id="address_error"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" 
                                   placeholder="Nhập mật khẩu" required>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-2">
                                <div class="progress" style="height: 8px;">
                                    <div id="passwordStrengthBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                                </div>
                                <small id="passwordStrengthText" class="text-muted"></small>
                            </div>
                            <small class="text-danger" id="password_error"></small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" 
                                   placeholder="Nhập lại mật khẩu" required>
                            <small class="text-danger" id="confirm_password_error"></small>
                        </div>
                        
                        <button type="submit" name="register" class="btn btn-primary w-100">Đăng ký</button>
                    </form>
                    
                    <p class="text-center mt-3">
                        Đã có tài khoản? <a href="index.php?page=login">Đăng nhập</a>
                    </p>
                    
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');
    
    let strength = 0;
    let label = '';
    let color = 'danger';
    
    // Check length
    if (password.length >= 8) {
        strength += 1;
    }
    
    // Check for letters
    if (/[a-zA-Z]/.test(password)) {
        strength += 1;
    }
    
    // Check for numbers
    if (/[0-9]/.test(password)) {
        strength += 1;
    }
    
    // Check for uppercase
    if (/[A-Z]/.test(password)) {
        strength += 1;
    }
    
    // Check for special characters
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
        strength += 1;
    }
    
    // Calculate strength percentage
    const percentage = Math.min(100, (strength / 5) * 100);
    strengthBar.style.width = percentage + '%';
    
    // Determine strength label
    if (password.length < 8) {
        label = 'Yếu - Cần ít nhất 8 ký tự';
        color = 'danger';
    } else if (strength < 3) {
        label = 'Trung bình - Cần thêm chữ và số';
        color = 'warning';
    } else if (strength < 5) {
        label = 'Khá - Nên thêm ký tự đặc biệt';
        color = 'info';
    } else {
        label = 'Mạnh';
        color = 'success';
    }
    
    strengthBar.className = 'progress-bar bg-' + color;
    strengthText.textContent = label;
    strengthText.className = 'text-' + color;
});

// Form validation
function validateRegisterForm() {
    let isValid = true;
    
    // Clear errors
    document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
    
    // Validate full name
    const fullName = document.getElementById('full_name').value.trim();
    if (!fullName) {
        document.getElementById('full_name_error').textContent = 'Vui lòng nhập họ tên!';
        isValid = false;
    }
    
    // Validate email
    const email = document.getElementById('email').value.trim();
    const emailRegex = /^[^\s@]+@gmail\.com$/;
    if (!email) {
        document.getElementById('email_error').textContent = 'Vui lòng nhập email!';
        isValid = false;
    } else if (!emailRegex.test(email)) {
        document.getElementById('email_error').textContent = 'Phải là địa chỉ Gmail (xxx@gmail.com)!';
        isValid = false;
    }
    
    // Validate phone
    const phone = document.getElementById('phone').value.trim();
    const phoneRegex = /^[0-9]{10}$/;
    if (!phone) {
        document.getElementById('phone_error').textContent = 'Vui lòng nhập số điện thoại!';
        isValid = false;
    } else if (!phoneRegex.test(phone)) {
        document.getElementById('phone_error').textContent = 'Số điện thoại phải có 10 chữ số!';
        isValid = false;
    }
    
    // Validate address
    const address = document.getElementById('address').value.trim();
    if (!address) {
        document.getElementById('address_error').textContent = 'Vui lòng nhập địa chỉ!';
        isValid = false;
    }
    
    // Validate password
    const password = document.getElementById('password').value;
    if (!password) {
        document.getElementById('password_error').textContent = 'Vui lòng nhập mật khẩu!';
        isValid = false;
    } else if (password.length < 8) {
        document.getElementById('password_error').textContent = 'Mật khẩu phải có ít nhất 8 ký tự!';
        isValid = false;
    }
    
    // Validate confirm password
    const confirmPassword = document.getElementById('confirm_password').value;
    if (password !== confirmPassword) {
        document.getElementById('confirm_password_error').textContent = 'Mật khẩu không khớp!';
        isValid = false;
    }
    
    return isValid;
}

// Real-time validation
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value.trim();
    const emailRegex = /^[^\s@]+@gmail\.com$/;
    if (email && !emailRegex.test(email)) {
        document.getElementById('email_error').textContent = 'Phải là địa chỉ Gmail!';
    } else {
        document.getElementById('email_error').textContent = '';
    }
});

document.getElementById('phone').addEventListener('blur', function() {
    const phone = this.value.trim();
    const phoneRegex = /^[0-9]{10}$/;
    if (phone && !phoneRegex.test(phone)) {
        document.getElementById('phone_error').textContent = 'Phải là 10 chữ số!';
    } else {
        document.getElementById('phone_error').textContent = '';
    }
});
</script>