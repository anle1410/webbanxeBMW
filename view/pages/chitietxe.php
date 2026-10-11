<?php
// View: Trang chi tiết xe
// Hiển thị thông tin chi tiết, ảnh và nút điều hướng cho người dùng

$mainImage = null;
if (!empty($xe_images)) {
    $mainImage = $xe_images[0]['image_url'];
}
$defaultImage = 'https://via.placeholder.com/800x500?text=No+Image';
?>

<div class="container py-5 mt-5">
    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <img src="<?php echo htmlspecialchars($mainImage ?: $defaultImage); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($xe_detail['vehicle_name']); ?>">
                <div class="card-body">
                    <h2 class="card-title"><?php echo htmlspecialchars($xe_detail['vehicle_name']); ?></h2>
                    <p class="text-muted mb-2">Mã xe: <?php echo htmlspecialchars($xe_detail['vehicle_code'] ?? '-'); ?></p>
                    <p class="text-muted mb-2">Danh mục: <?php echo htmlspecialchars($xe_detail['category_name'] ?? '-'); ?></p>
                    <p class="text-muted mb-2">Năm sản xuất: <?php echo htmlspecialchars($xe_detail['model_year'] ?? '-'); ?></p>
                    <p class="fs-4 text-danger mb-3">
                        <?php if (!empty($xe_detail['promotional_price']) && $xe_detail['promotional_price'] > 0): ?>
                            <?php echo number_format($xe_detail['promotional_price'], 0, ',', '.'); ?> đ
                            <small class="text-muted text-decoration-line-through ms-2"><?php echo number_format($xe_detail['price'], 0, ',', '.'); ?> đ</small>
                        <?php else: ?>
                            <?php echo number_format($xe_detail['price'], 0, ',', '.'); ?> đ
                        <?php endif; ?>
                    </p>
                    <div class="mb-4">
                        <h5>Mô tả chi tiết</h5>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($xe_detail['description'] ?? 'Chưa có mô tả cho xe này.')); ?></p>
                    </div>

                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Loại động cơ:</strong> <?php echo htmlspecialchars($xe_detail['engine_type'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Dung tích động cơ:</strong> <?php echo htmlspecialchars($xe_detail['engine_displacement'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Công suất:</strong> <?php echo htmlspecialchars($xe_detail['horsepower'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Mô-men xoắn:</strong> <?php echo htmlspecialchars($xe_detail['torque'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Hộp số:</strong> <?php echo htmlspecialchars($xe_detail['transmission'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Hệ dẫn động:</strong> <?php echo htmlspecialchars($xe_detail['drivetrain'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Loại nhiên liệu:</strong> <?php echo htmlspecialchars($xe_detail['fuel_type'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Mức tiêu hao nhiên liệu:</strong> <?php echo htmlspecialchars($xe_detail['fuel_efficiency'] ?? '-'); ?></li>
                            </ul>
                        </div>
                        <div class="col">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Tăng tốc 0-100 km/h:</strong> <?php echo htmlspecialchars($xe_detail['acceleration'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Tốc độ tối đa:</strong> <?php echo htmlspecialchars($xe_detail['top_speed'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Kích thước (DxRxC):</strong> <?php echo htmlspecialchars(trim(($xe_detail['dimensions_length'] ?? '') . ' x ' . ($xe_detail['dimensions_width'] ?? '') . ' x ' . ($xe_detail['dimensions_height'] ?? '')) ?: '-'); ?></li>
                                <li class="list-group-item"><strong>Chiều dài cơ sở:</strong> <?php echo htmlspecialchars($xe_detail['wheelbase'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Trọng lượng:</strong> <?php echo htmlspecialchars($xe_detail['curb_weight'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Số chỗ ngồi:</strong> <?php echo !empty($xe_detail['seating_capacity']) ? intval($xe_detail['seating_capacity']) : '-'; ?></li>
                                <li class="list-group-item"><strong>Màu ngoại thất:</strong> <?php echo htmlspecialchars($xe_detail['colors'] ?? '-'); ?></li>
                                <li class="list-group-item"><strong>Màu nội thất:</strong> <?php echo htmlspecialchars($xe_detail['interior_color'] ?? '-'); ?></li>
                            </ul>
                        </div>
                    </div>

                    <?php if (!empty($xe_detail['features'])): ?>
                        <div class="mt-4">
                            <h5>Tính năng nổi bật</h5>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($xe_detail['features'])); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <!-- Form thêm sản phẩm vào giỏ hàng -->
                        <form method="POST" action="index.php?page=chitietxe&id=<?php echo intval($xe_detail['vehicle_id']); ?>" class="d-inline">
                            <input type="hidden" name="vehicle_id" value="<?php echo intval($xe_detail['vehicle_id']); ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-outline-success">Thêm vào giỏ hàng</button>
                        </form>
                        <!-- Mua ngay chuyển thẳng tới trang thanh toán với xe này -->
                        <a href="index.php?page=thanhtoan&vehicle_id=<?php echo intval($xe_detail['vehicle_id']); ?>" class="btn btn-success">Mua ngay</a>
                        <a href="index.php?page=giohang" class="btn btn-outline-primary">Xem giỏ hàng</a>
                        <a href="index.php?page=testdrive&vehicle_id=<?php echo intval($xe_detail['vehicle_id']); ?>" class="btn btn-primary">Đăng ký lái thử</a>
                    </div>
                </div>
            </div>

            <?php if (!empty($xe_images)): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Bộ sưu tập ảnh xe</h5>
                        <div class="row g-3">
                            <?php foreach ($xe_images as $image): ?>
                                <div class="col-6 col-md-4">
                                    <img src="<?php echo htmlspecialchars($image['image_url']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($xe_detail['vehicle_name']); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($related_xe)): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Xe liên quan</h5>
                        <div class="row g-3">
                            <?php foreach ($related_xe as $item): ?>
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <img src="<?php echo htmlspecialchars($item['image_url'] ?? 'https://via.placeholder.com/400x250'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['vehicle_name']); ?>">
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo htmlspecialchars($item['vehicle_name']); ?></h6>
                                            <p class="card-text text-danger mb-2"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</p>
                                            <a href="index.php?page=chitietxe&id=<?php echo intval($item['vehicle_id']); ?>" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Thông tin nhanh</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Tên xe:</strong> <?php echo htmlspecialchars($xe_detail['vehicle_name']); ?></li>
                        <li class="list-group-item"><strong>Mã xe:</strong> <?php echo htmlspecialchars($xe_detail['vehicle_code']); ?></li>
                        <li class="list-group-item"><strong>Danh mục:</strong> <?php echo htmlspecialchars($xe_detail['category_name'] ?? '-'); ?></li>
                        <li class="list-group-item"><strong>Giá gốc:</strong> <?php echo number_format($xe_detail['price'], 0, ',', '.'); ?> đ</li>
                        <?php if (!empty($xe_detail['promotional_price']) && $xe_detail['promotional_price'] > 0): ?>
                            <li class="list-group-item"><strong>Giá khuyến mãi:</strong> <?php echo number_format($xe_detail['promotional_price'], 0, ',', '.'); ?> đ</li>
                        <?php endif; ?>
                        <li class="list-group-item"><strong>Năm sản xuất:</strong> <?php echo htmlspecialchars($xe_detail['model_year'] ?? '-'); ?></li>
                        <li class="list-group-item"><strong>Số chỗ:</strong> <?php echo !empty($xe_detail['seating_capacity']) ? intval($xe_detail['seating_capacity']) : '-'; ?></li>
                        <li class="list-group-item"><strong>Trạng thái:</strong> <?php echo htmlspecialchars($xe_detail['status'] ?? '-'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
