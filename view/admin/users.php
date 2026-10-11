<?php
// Admin - Manage Users

// Get all users
$all_users = $user->getAll();

// Handle actions
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $user->delete($user_id);
    echo "<script>window.location.href='index.php?page=admin_users';</script>";
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'status' && isset($_GET['id']) && isset($_GET['status'])) {
    $user_id = intval($_GET['id']);
    $status = $_GET['status'];
    $user->updateStatus($user_id, $status);
    echo "<script>window.location.href='index.php?page=admin_users';</script>";
    exit();
}
?>

<div class="admin-content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-3">
                <?php include 'view/admin/sidebar.php'; ?>
            </div>
            <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Quản lý Users</h2>
            <a href="index.php?page=admin" class="btn btn-secondary">← Back</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($all_users as $u): ?>
                        <tr>
                            <td><?php echo $u['user_id']; ?></td>
                            <td><?php echo $u['full_name']; ?></td>
                            <td><?php echo $u['email']; ?></td>
                            <td><?php echo $u['phone']; ?></td>
                            <td>
                                <?php if($u['role'] == 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $status_class = [
                                    'active' => 'success',
                                    'inactive' => 'warning',
                                    'banned' => 'danger'
                                ];
                                ?>
                                <span class="badge bg-<?php echo $status_class[$u['status']] ?? 'secondary'; ?>">
                                    <?php echo $u['status']; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($u['created_at'])); ?></td>
                            <td>
                                <?php if($u['user_id'] != $_SESSION['user_id']): ?>
                                <div class="btn-group">
                                    <?php if($u['status'] == 'active'): ?>
                                        <a href="?page=admin_users&action=status&id=<?php echo $u['user_id']; ?>&status=inactive" class="btn btn-sm btn-warning">Khóa</a>
                                    <?php else: ?>
                                        <a href="?page=admin_users&action=status&id=<?php echo $u['user_id']; ?>&status=active" class="btn btn-sm btn-success">Mở</a>
                                    <?php endif; ?>
                                    <a href="?page=admin_users&action=delete&id=<?php echo $u['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn?')">Xóa</a>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>

<style>
.admin-content {
    margin-top: 80px;
    background: #1a1a1a;
    min-height: calc(100vh - 80px);
}
.card {
    border: none;
    border-radius: 10px;
}
</style>