<?php
// View: Trang đăng nhập
// Hiển thị form login, thông báo lỗi, xác minh email và redirect về trang trước khi đăng nhập

global $login_error, $login_success, $verify_error, $verify_success;
$verification_email = isset($_GET['verify_email']) ? $_GET['verify_email'] : '';
$resend_token = isset($_GET['resend']) ? $_GET['resend'] : '';
$verified = isset($_GET['verified']);
$verify_error_flag = isset($_GET['verify_error']);
$redirect_url = '';
if (!empty($_POST['redirect_url'])) {
    $redirect_url = $_POST['redirect_url'];
} elseif (!empty($_GET['redirect'])) {
    $redirect_url = $_GET['redirect'];
}
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Đăng nhập</h3>
                    
                    <?php if(isset($login_error)): ?>
                        <div class="alert alert-danger"><?php echo $login_error; ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($verify_error)): ?>
                        <div class="alert alert-danger"><?php echo $verify_error; ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($login_success)): ?>
                        <div class="alert alert-success"><?php echo $login_success; ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($verify_success) || $verified): ?>
                        <div class="alert alert-success"><?php echo $verify_success ?? 'Xác minh email thành công! Bạn có thể đăng nhập.'; ?></div>
                    <?php endif; ?>
                    
                    <?php if(isset($verify_error) || $verify_error_flag): ?>
                        <div class="alert alert-danger"><?php echo $verify_error ?? 'Link xác minh không hợp lệ hoặc đã hết hạn!'; ?></div>
                    <?php endif; ?>
                    
                    <?php if($verification_email): ?>
                        <div class="alert alert-warning">
                            <h5>Email chưa được xác minh!</h5>
                            <p>Vui lòng kiểm tra email <strong><?php echo $verification_email; ?></strong> để xác minh tài khoản.</p>
                            <form method="POST" action="index.php?page=resend_verify" class="mt-3">
                                <input type="hidden" name="resend_email" value="<?php echo $verification_email; ?>">
                                <button type="submit" name="resend_verification" class="btn btn-outline-primary w-100">
                                    Gửi lại email xác minh
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($resend_token == 'success'): ?>
                        <div class="alert alert-success">
                            <h5>Đã gửi lại email xác minh!</h5>
                            <p>Vui lòng kiểm tra hòm thư email của bạn.</p>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="loginForm">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <?php if (!empty($redirect_url)): ?>
                            <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($redirect_url); ?>">
                        <?php endif; ?>
                        <button type="submit" name="login" class="btn btn-primary w-100">Đăng nhập</button>
                    </form>
                    
                    <p class="text-center mt-3">
                        Chưa có tài khoản? <a href="index.php?page=register">Đăng ký</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>