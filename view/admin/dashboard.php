<?php
// Admin Dashboard

// Get statistics
$total_users = $user->count();
$total_users_admin = $user->count('admin');
$total_vehicles = $xe->count();
$total_orders = $order->count();
$total_orders_pending = $order->count('pending');
$total_testdrives = $testdrive->count();
$total_testdrives_pending = $testdrive->count('pending');

// Get recent orders
$recent_orders = $order->getRecent(5);

// Get revenue
$revenue = $order->getRevenue();
?>

<div class="admin-content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-3">
                <?php include 'view/admin/sidebar.php'; ?>
            </div>
            <div class="col-md-9">
                <h2 class="mb-4">Dashboard - Admin</h2>
        
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Users</h5>
                        <h3><?php echo $total_users; ?></h3>
                        <small><?php echo $total_users_admin; ?> admin</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Vehicles</h5>
                        <h3><?php echo $total_vehicles; ?></h3>
                        <small>Xe đang bán</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5>Orders</h5>
                        <h3><?php echo $total_orders; ?></h3>
                        <small><?php echo $total_orders_pending; ?> chờ xử lý</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>Test Drives</h5>
                        <h3><?php echo $total_testdrives; ?></h3>
                        <small><?php echo $total_testdrives_pending; ?> chờ xác nhận</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Doanh thu</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="text-success"><?php echo number_format($revenue['revenue'] ?? 0, 0, ',', '.'); ?> ₫</h3>
                        <small><?php echo $revenue['total_orders'] ?? 0; ?> đơn hàng đã hoàn thành</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Menu quản trị</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="index.php?page=admin_users" class="btn btn-primary">Quản lý Users</a>
                            <a href="index.php?page=admin_vehicles" class="btn btn-success">Quản lý Xe</a>
                            <a href="index.php?page=admin_orders" class="btn btn-warning">Quản lý Đơn hàng</a>
                            <?php $msgModel = new MessageModel(); $unread_count = $msgModel->getUnreadCount(); ?>
                            <a href="index.php?page=admin_messages" class="btn btn-secondary position-relative">Quản lý Tin nhắn
                                <?php if ($unread_count > 0): ?>
                                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill"><?php echo $unread_count; ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="index.php?page=admin_testdrive" class="btn btn-info">Quản lý Test Drive</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Đơn hàng gần đây</h5>
            </div>
            <div class="card-body">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_orders as $ord): ?>
                        <tr>
                            <td><?php echo $ord['order_number']; ?></td>
                            <td><?php echo $ord['full_name']; ?></td>
                            <td><?php echo number_format($ord['total_amount'], 0, ',', '.'); ?> ₫</td>
                            <td>
                                <?php 
                                $status_class = [
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $class = $status_class[$ord['order_status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $class; ?>"><?php echo $ord['order_status']; ?></span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($ord['created_at'])); ?></td>
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
.table-dark {
    --bs-table-bg: #1a1a1a;
}
</style>