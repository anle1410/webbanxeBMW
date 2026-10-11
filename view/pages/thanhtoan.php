<?php
// View: Thanh toan
global $user_id;

$user_model = new User();
$user_info = $user_model->getById($user_id);
$direct_checkout = isset($direct_checkout) && $direct_checkout;
?>

<div class="container py-5 mt-5">
    <h2 class="mb-4">Thanh toán</h2>
    
    <form method="POST">
        <?php if ($direct_checkout && !empty($cart_items[0])): ?>
            <input type="hidden" name="vehicle_id" value="<?php echo intval($cart_items[0]['vehicle_id']); ?>">
            <input type="hidden" name="quantity" value="<?php echo intval($cart_items[0]['quantity']); ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin khách hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên *</label>
                                <input type="text" name="customer_name" class="form-control" value="<?php echo htmlspecialchars($user_info['full_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại *</label>
                                <input type="tel" name="customer_phone" class="form-control" value="<?php echo htmlspecialchars($user_info['phone'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="customer_email" class="form-control" value="<?php echo htmlspecialchars($user_info['email'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Địa chỉ giao hàng *</label>
                                <textarea name="shipping_address" class="form-control" rows="2" required><?php echo htmlspecialchars($user_info['address'] ?? ''); ?></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="order_notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Phương thức thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="cash" checked>
                            <label class="form-check-label">
                                Tiền mặt khi nhận xe
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer">
                            <label class="form-check-label">
                                Chuyển khoản ngân hàng
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" value="installment">
                            <label class="form-check-label">
                                Trả góp
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($cart_items)): ?>
                            <?php foreach ($cart_items as $item): ?>
                                <?php
                                $unit_price = isset($item['unit_price']) ? $item['unit_price'] : ($item['promotional_price'] ? $item['promotional_price'] : $item['price']);
                                $quantity = !empty($item['quantity']) ? intval($item['quantity']) : 1;
                                $subtotal_item = isset($item['subtotal']) ? $item['subtotal'] : ($unit_price * $quantity);
                                ?>
                                <div class="mb-3">
                                    <div class="fw-semibold"><?php echo htmlspecialchars($item['vehicle_name']); ?></div>
                                    <div class="small text-muted">Mã xe: <?php echo htmlspecialchars($item['vehicle_code'] ?? '-'); ?></div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>Số lượng</span>
                                        <span><?php echo $quantity; ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Giá</span>
                                        <span><?php echo number_format($unit_price, 0, ',', '.'); ?> ₫</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Tạm tính</span>
                                        <span><?php echo number_format($subtotal_item, 0, ',', '.'); ?> ₫</span>
                                    </div>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">Không có sản phẩm nào để thanh toán.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Tóm tắt thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span><?php echo number_format($cart_total['subtotal'] ?? 0, 0, ',', '.'); ?> ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Thuế (10%):</span>
                            <span><?php echo number_format($cart_total['tax'] ?? 0, 0, ',', '.'); ?> ₫</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Tổng cộng:</strong>
                            <strong class="text-danger"><?php echo number_format($cart_total['total'] ?? 0, 0, ',', '.'); ?> ₫</strong>
                        </div>
                        <button type="submit" name="checkout" class="btn btn-primary w-100">Xác nhận đơn hàng</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>