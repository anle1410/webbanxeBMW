<?php
// View: Don hang
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>

<div class="container py-5 mt-5">
    <h2 class="mb-4">Đơn hàng của tôi</h2>
    
    <?php if($success): ?>
    <div class="alert alert-success">
        <h4>Đặt hàng thành công!</h4>
        <p>Mã đơn hàng: <strong><?php echo htmlspecialchars($success); ?></strong></p>
        <p>Cảm ơn bạn đã mua sắm tại BMW Showroom!</p>
    </div>
    <?php endif; ?>
    
    <?php if(!empty($orders)): ?>
    <div class="row">
        <?php foreach($orders as $o): ?>
        <?php
            $status_class = [
                'pending' => 'warning',
                'confirmed' => 'info',
                'packed' => 'info',
                'preparing' => 'info',
                'received' => 'primary',
                'shipping' => 'primary',
                'delivering' => 'primary',
                'completed' => 'success',
                'cancelled' => 'danger'
            ];

            $progress_steps = [
                ['label' => 'Đã duyệt hàng', 'statuses' => ['confirmed']],
                ['label' => 'Đã đóng gói', 'statuses' => ['packed', 'preparing']],
                ['label' => 'Đã nhận hàng', 'statuses' => ['received']],
                ['label' => 'Đang trên đường giao', 'statuses' => ['shipping', 'delivering']],
                ['label' => 'Giao thành công', 'statuses' => ['completed']],
            ];

            $current_step = -1;
            if (in_array($o['order_status'], ['confirmed', 'packed', 'preparing', 'received', 'shipping', 'delivering', 'completed'])) {
                $current_step = 0;
            }
        ?>
        <div class="col-md-12 mb-4">
            <div class="card order-card shadow-sm border-0 overflow-hidden">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center p-4 border-0 bg-light">
                    <div>
                        <h5 class="mb-1">Mã đơn hàng: <span class="text-primary"><?php echo $o['order_number']; ?></span></h5>
                        <p class="mb-0 text-muted">Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($o['created_at'])); ?></p>
                    </div>
                    <span class="badge rounded-pill px-3 py-2 bg-<?php echo $status_class[$o['order_status']] ?? 'secondary'; ?> text-white">
                        <?php echo $o['order_status']; ?>
                    </span>
                </div>

                <div class="card-body p-4">
                    <div class="row gx-4 gy-4">
                        <div class="col-lg-6">
                            <div class="info-panel p-4 rounded-4 shadow-sm h-100 bg-white">
                                <h6 class="text-uppercase text-secondary mb-3">Chi tiết đơn hàng</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Tổng tiền</span>
                                        <strong><?php echo number_format($o['total_amount'], 0, ',', '.'); ?> ₫</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Thanh toán</span>
                                        <span class="text-dark"><?php echo htmlspecialchars($o['payment_status']); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Phương thức</span>
                                        <span class="text-dark"><?php echo htmlspecialchars($o['payment_method']); ?></span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <p class="mb-2 fw-semibold">Địa chỉ giao hàng</p>
                                    <p class="mb-0 text-muted small"><?php echo nl2br(htmlspecialchars($o['shipping_address'])); ?></p>
                                </div>
                                <?php if (!empty($o['shipping_address'])): ?>
                                    <div class="map-card rounded-4 overflow-hidden mt-4">
                                        <iframe
                                            src="https://maps.google.com/maps?q=<?php echo urlencode($o['shipping_address']); ?>&output=embed"
                                            style="border:0; width:100%; height:260px;"
                                            allowfullscreen=""
                                            loading="lazy">
                                        </iframe>
                                    </div>
                                    <p class="small text-muted mt-3 mb-0">Bản đồ mô phỏng vị trí giao hàng.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="order-progress-card p-4 rounded-4 shadow-sm h-100 bg-gradient-light">
                                <h6 class="text-uppercase text-secondary mb-4">Tiến trình giao hàng</h6>
                                <div class="progress-steps">
                                    <?php foreach ($progress_steps as $step_index => $step): ?>
                                        <?php $active = $step_index === $current_step; ?>
                                        <div class="progress-step<?php echo $active ? ' active' : ''; ?>">
                                            <div class="step-marker<?php echo $active ? ' active' : ''; ?>">
                                                <?php echo $step_index + 1; ?>
                                            </div>
                                            <div class="step-text">
                                                <strong><?php echo $step['label']; ?></strong>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <?php if ($o['order_status'] === 'pending'): ?>
                                    <div class="status-box mt-4 rounded-4 p-3 bg-warning text-dark">
                                        <div class="fw-semibold mb-2">Đang chờ duyệt</div>
                                        <div>Đơn hàng sẽ được xác nhận bởi bộ phận bán hàng trong vòng ít phút.</div>
                                    </div>
                                <?php elseif ($o['order_status'] === 'cancelled'): ?>
                                    <div class="status-box mt-4 rounded-4 p-3 bg-danger text-white">
                                        <div class="fw-semibold mb-2">Đã hủy!</div>
                                        <div>Đơn hàng bị hủy. Vui lòng liên hệ admin nếu cần hỗ trợ.</div>
                                    </div>
                                <?php elseif (!in_array($o['order_status'], ['pending', 'cancelled'])): ?>
                                    <div class="status-box mt-4 rounded-4 p-3 bg-white text-dark">
                                        <div class="fw-semibold mb-2">Trạng thái hiện tại</div>
                                        <div>Đơn hàng đang ở giai đoạn: <strong><?php echo htmlspecialchars($o['order_status']); ?></strong>.</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
        <h4>Bạn chưa có đơn hàng nào</h4>
        <a href="index.php?page=danhmuc" class="btn btn-primary">Xem danh sách xe</a>
    </div>
    <?php endif; ?>
</div>

<style>
.order-card {
    background: linear-gradient(180deg, #ffffff 0%, #f7f9fc 100%);
}
.info-panel {
    background: #ffffff;
    border: 1px solid rgba(0,0,0,0.05);
}
.order-progress-card {
    background: linear-gradient(180deg, #f8fbff 0%, #eef6ff 100%);
    border: 1px solid rgba(13,110,253,0.15);
}
.progress-steps {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.progress-step {
    display: flex;
    align-items: center;
    gap: 1rem;
    opacity: 0.55;
    transition: opacity 0.2s ease;
}
.progress-step.active {
    opacity: 1;
}
.step-marker {
    width: 44px;
    height: 44px;
    min-width: 44px;
    border-radius: 50%;
    background: #eff2f7;
    color: #6c757d;
    display: grid;
    place-items: center;
    font-weight: 700;
    border: 2px solid transparent;
    transition: all 0.2s ease;
}
.step-marker.active {
    background: #0d6efd;
    color: #ffffff;
    border-color: #0d6efd;
    box-shadow: 0 10px 24px rgba(13,110,253,0.18);
}
.step-text strong {
    font-size: 1rem;
    color: #6c757d;
}
.progress-step.active .step-text strong {
    color: #212529;
}
.progress-step {
    padding: 0.5rem 0;
}
.status-box {
    border: 1px solid rgba(13,110,253,0.15);
}
.bg-gradient-light {
    background: linear-gradient(180deg, #f5f8ff 0%, #e9f0ff 100%) !important;
}
.map-card {
    background: #f8f9fa;
}
</style>