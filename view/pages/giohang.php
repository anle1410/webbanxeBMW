<?php
// View: Trang giỏ hàng
// Hiển thị danh sách sản phẩm trong giỏ, hỗ trợ cập nhật số lượng và xóa sản phẩm
?>

<div class="container py-5 mt-5">
    <h2 class="mb-4">Giỏ hàng</h2>
    
    <?php if(!empty($cart_items)): ?>
    <form method="POST" action="index.php?page=giohang">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <?php foreach($cart_items as $index => $item): ?>
                        <?php $price = $item['promotional_price'] ? $item['promotional_price'] : $item['price']; ?>
                        <div class="row align-items-center border-bottom py-3">
                            <input type="hidden" name="cart_id[]" value="<?php echo intval($item['cart_id']); ?>">
                            <div class="col-md-2">
                                <img src="<?php echo htmlspecialchars($item['image_url'] ?? 'https://via.placeholder.com/150x100'); ?>" alt="<?php echo htmlspecialchars($item['vehicle_name']); ?>" class="img-fluid rounded">
                            </div>
                            <div class="col-md-4">
                                <h5><?php echo htmlspecialchars($item['vehicle_name']); ?></h5>
                                <small class="text-muted"><?php echo htmlspecialchars($item['vehicle_code']); ?></small>
                            </div>
                            <div class="col-md-2 text-center">
                                <?php echo number_format($price, 0, ',', '.'); ?> ₫
                            </div>
                            <div class="col-md-2 text-center">
                                <input type="number" name="quantity[]" value="<?php echo intval($item['quantity']); ?>" min="1" class="form-control text-center" style="max-width: 90px; margin: 0 auto;">
                            </div>
                            <div class="col-md-2 text-end">
                                <strong><?php echo number_format($price * $item['quantity'], 0, ',', '.'); ?> ₫</strong>
                                <div class="mt-2">
                                    <button type="submit" name="remove_cart" value="<?php echo intval($item['cart_id']); ?>" class="btn btn-sm btn-outline-danger">
                                        Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5>Tổng cộng</h5>
                    <hr>
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
                        <strong>Tổng:</strong>
                        <strong class="text-danger"><?php echo number_format($cart_total['total'] ?? 0, 0, ',', '.'); ?> ₫</strong>
                    </div>
                    <button type="submit" name="update_cart" class="btn btn-outline-primary w-100 mb-2">Cập nhật giỏ hàng</button>
                    <a href="index.php?page=thanhtoan" class="btn btn-primary w-100">Thanh toán từ giỏ hàng</a>
                    <a href="index.php?page=danhmuc" class="btn btn-outline-secondary w-100 mt-2">Tiếp tục mua</a>
                    <!-- Nút cập nhật gửi form chứa cart_id[] và quantity[] lên index.php?page=giohang -->
                </div>
            </div>
        </div>
    </div>
    </form>
    <?php else: ?>
    <div class="text-center py-5">
        <h4>Giỏ hàng trống</h4>
        <p>Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
        <a href="index.php?page=danhmuc" class="btn btn-primary">Xem danh sách xe</a>
    </div>
    <?php endif; ?>
</div>