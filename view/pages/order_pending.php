<?php
// View: Order Pending Approval
$pending_order = $pending_order ?? null;
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-warning mb-4">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Đơn hàng đang chờ duyệt</h4>
                </div>
                <div class="card-body">
                    <?php if ($pending_order): ?>
                        <p>Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đang được bộ phận bán hàng kiểm tra và duyệt.</p>
                        <div class="mb-3">
                            <strong>Mã đơn hàng:</strong> <?php echo htmlspecialchars($pending_order['order_number']); ?>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card p-3 bg-light">
                                    <h5>Thông tin khách hàng</h5>
                                    <p><strong>Họ và tên:</strong> <?php echo htmlspecialchars($pending_order['customer_name']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($pending_order['customer_email']); ?></p>
                                    <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($pending_order['customer_phone']); ?></p>
                                    <p><strong>Địa chỉ giao hàng:</strong><br><?php echo nl2br(htmlspecialchars($pending_order['shipping_address'])); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card p-3 bg-light">
                                    <h5>Thông tin đơn hàng</h5>
                                    <p><strong>Tổng tiền:</strong> <?php echo number_format($pending_order['total_amount'], 0, ',', '.'); ?> ₫</p>
                                    <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($pending_order['payment_method']); ?></p>
                                    <p><strong>Trạng thái đơn:</strong> <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($pending_order['order_status']); ?></span></p>
                                    <p><strong>Ghi chú:</strong><br><?php echo nl2br(htmlspecialchars($pending_order['order_notes'])); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="index.php?page=donhang" class="btn btn-primary">Xem đơn hàng của tôi</a>
                            <a href="index.php?page=danhmuc" class="btn btn-outline-secondary">Tiếp tục mua sắm</a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">Không tìm thấy thông tin đơn hàng. Vui lòng kiểm tra lại.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
