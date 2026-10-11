<?php
// Admin - Manage Orders

// Get all orders
$all_orders = $order->getAll();

// Handle status update
if (isset($_GET['action']) && $_GET['action'] == 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = intval($_GET['id']);
    $status = $_GET['status'];
    $order->updateStatus($order_id, $status);
    echo "<script>window.location.href='index.php?page=admin_orders';</script>";
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
            <h2>Quản lý Đơn hàng</h2>
            <a href="index.php?page=admin" class="btn btn-secondary">← Back</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Địa chỉ giao hàng</th>
                            <th>Ghi chú</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($all_orders as $o): ?>
                        <tr>
                            <td><?php echo $o['order_number']; ?></td>
                            <td><?php echo htmlspecialchars($o['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($o['customer_email']); ?></td>
                            <td><?php echo htmlspecialchars($o['customer_phone']); ?></td>
                            <td><?php echo htmlspecialchars($o['shipping_address']); ?></td>
                            <td><?php echo htmlspecialchars($o['order_notes']); ?></td>
                            <td><?php echo number_format($o['total_amount'], 0, ',', '.'); ?> ₫</td>
                            <td>
                                <?php 
                                $pay_class = [
                                    'unpaid' => 'warning',
                                    'paid' => 'success',
                                    'partial' => 'info'
                                ];
                                ?>
                                <span class="badge bg-<?php echo $pay_class[$o['payment_status']] ?? 'secondary'; ?>">
                                    <?php echo $o['payment_status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                $status_class = [
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'preparing' => 'info',
                                    'shipping' => 'primary',
                                    'delivering' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                ?>
                                <span class="badge bg-<?php echo $status_class[$o['order_status']] ?? 'secondary'; ?>">
                                    <?php echo $o['order_status']; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($o['created_at'])); ?></td>
                            <td>
                                <div class="btn-group">
                                    <?php if($o['order_status'] == 'pending'): ?>
                                        <a href="?page=admin_orders&action=update_status&id=<?php echo $o['order_id']; ?>&status=confirmed" class="btn btn-sm btn-info">Xác nhận</a>
                                        <a href="?page=admin_orders&action=update_status&id=<?php echo $o['order_id']; ?>&status=cancelled" class="btn btn-sm btn-danger">Hủy</a>
                                <?php elseif($o['order_status'] == 'confirmed'): ?>
                                        <a href="?page=admin_orders&action=update_status&id=<?php echo $o['order_id']; ?>&status=packed" class="btn btn-sm btn-secondary">Đóng gói</a>
                                        <a href="?page=admin_orders&action=update_status&id=<?php echo $o['order_id']; ?>&status=cancelled" class="btn btn-sm btn-danger">Hủy</a>
                                <?php elseif(in_array($o['order_status'], ['packed','preparing'])): ?>
                                        <a href="?page=admin_orders&action=update_status&id=<?php echo $o['order_id']; ?>&status=received" class="btn btn-sm btn-primary">Đã nhận</a>
                                        <a href="?page=admin_orders&action=update_status&id=<?php echo $o['order_id']; ?>&status=cancelled" class="btn btn-sm btn-danger">Hủy</a>
                                <?php elseif($o['order_status'] == 'received'): ?>
                                        <a href="?page=admin_orders&action=update_status&id=<?php echo $o['order_id']; ?>&status=shipping" class="btn btn-sm btn-primary">Đang giao</a>
                                        <a href="?page=admin_orders&action=update_status&id=<?php echo $o['order_id']; ?>&status=cancelled" class="btn btn-sm btn-danger">Hủy</a>
                                <?php elseif(in_array($o['order_status'], ['shipping','delivering'])): ?>
                                        <a href="?page=admin_orders&action=update_status&id=<?php echo $o['order_id']; ?>&status=completed" class="btn btn-sm btn-success">Giao thành công</a>
                                        <a href="?page=admin_orders&action=update_status&id=<?php echo $o['order_id']; ?>&status=cancelled" class="btn btn-sm btn-danger">Hủy</a>
                                <?php endif; ?>
                                </div>
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