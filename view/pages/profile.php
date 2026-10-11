<?php
// View: Profile
global $user_id, $profile_success, $profile_error;

$user_model = new User();
$user_info = $user_model->getById($user_id);
$avatar_url = !empty($user_info['avatar']) && file_exists(__DIR__ . '/../../' . $user_info['avatar'])
    ? $user_info['avatar']
    : null;
?>

<div class="container py-5 mt-5">
    <h2 class="mb-4">Tài khoản của tôi</h2>
    
    <?php if(isset($profile_error)): ?>
        <div class="alert alert-danger"><?php echo $profile_error; ?></div>
    <?php endif; ?>
    <?php if(isset($profile_success)): ?>
        <div class="alert alert-success"><?php echo $profile_success; ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar mb-3">
                        <?php if ($avatar_url): ?>
                            <img src="<?php echo $avatar_url; ?>" alt="Avatar" class="rounded-circle" style="width:120px; height:120px; object-fit:cover;">
                        <?php else: ?>
                            <i class="fas fa-user-circle fa-5x text-secondary"></i>
                        <?php endif; ?>
                    </div>
                    <h4><?php echo $user_info['full_name']; ?></h4>
                    <p class="text-muted"><?php echo $user_info['email']; ?></p>
                    <span class="badge bg-<?php echo $user_info['role'] == 'admin' ? 'danger' : 'primary'; ?>">
                        <?php echo $user_info['role'] == 'admin' ? 'Admin' : 'User'; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin tài khoản</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?page=profile" enctype="multipart/form-data">
                        <div class="mb-3 text-start">
                            <label class="form-label">Avatar</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user_info['full_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user_info['email']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($user_info['phone']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($user_info['address']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ngày tham gia</label>
                            <input type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($user_info['created_at'])); ?>" readonly>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-success">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
            
            <?php if($_SESSION['user_role'] == 'admin'): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Quản trị</h5>
                </div>
                <div class="card-body">
                    <a href="index.php?page=admin" class="btn btn-primary">Vào trang quản trị</a>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="card mt-3">
                <div class="card-body">
                    <a href="index.php?page=logout" class="btn btn-danger">Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>
</div>