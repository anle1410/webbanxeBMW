<?php
// Admin - Manage Test Drives

// Get all test drives
$all_testdrives = $testdrive->getAll();

// Handle status update
if (isset($_GET['action']) && $_GET['action'] == 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $testdrive_id = intval($_GET['id']);
    $status = $_GET['status'];
    $testdrive->updateStatus($testdrive_id, $status);
    echo "<script>window.location.href='index.php?page=admin_testdrive';</script>";
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
                    <h2>Quản lý Test Drive</h2>
                    <a href="index.php?page=admin" class="btn btn-secondary">← Back</a>
                </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Điện thoại</th>
                            <th>Email</th>
                            <th>Xe</th>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th>Địa điểm</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($all_testdrives as $td): ?>
                        <tr>
                            <td><?php echo $td['test_drive_id']; ?></td>
                            <td><?php echo $td['full_name']; ?></td>
                            <td><?php echo $td['phone']; ?></td>
                            <td><?php echo $td['email']; ?></td>
                            <td><?php echo $td['vehicle_name']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($td['desired_date'])); ?></td>
                            <td><?php echo date('H:i', strtotime($td['desired_time'])); ?></td>
                            <td><?php echo $td['pickup_location']; ?></td>
                            <td>
                                <?php 
                                $status_class = [
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                ?>
                                <span class="badge bg-<?php echo $status_class[$td['status']] ?? 'secondary'; ?>">
                                    <?php echo $td['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if($td['status'] == 'pending'): ?>
                                <div class="btn-group">
                                    <a href="?page=admin_testdrive&action=update_status&id=<?php echo $td['test_drive_id']; ?>&status=confirmed" class="btn btn-sm btn-info">Xác nhận</a>
                                    <a href="?page=admin_testdrive&action=update_status&id=<?php echo $td['test_drive_id']; ?>&status=cancelled" class="btn btn-sm btn-danger">Hủy</a>
                                </div>
                                <?php elseif($td['status'] == 'confirmed'): ?>
                                <a href="?page=admin_testdrive&action=update_status&id=<?php echo $td['test_drive_id']; ?>&status=completed" class="btn btn-sm btn-success">Hoàn thành</a>
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