<?php
// View: Trang chủ
?>

<!-- Hero Slider -->
<section class="hero-slider position-relative min-vh-100 d-flex align-items-center">
    <!-- Slide 1: BMW M4 -->
    <div class="slide active" style="background: linear-gradient(135deg, rgba(0,0,0,0.9), rgba(0,102,177,0.3)), url('https://images.unsplash.com/photo-1617788138017-80a406023947?w=1920') center/cover;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="banner-content">
                        <span class="badge bg-warning text-dark mb-3">MỚI 2024</span>
                        <h1 class="banner-title reveal">BMW M4<br><span class="text-gradient">COMPETITION</span></h1>
                        <p class="banner-subtitle">Sức mạnh 510 HP | 0-100 km/h trong 3.8s<br>Biểu tượng của đẳng cấp thể thao</p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="index.php?page=danhmuc" class="btn btn-primary btn-glow">Khám phá ngay</a>
                            <a href="index.php?page=testdrive" class="btn btn-outline">Lái thử miễn phí</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Slide 2: BMW X7 -->
    <div class="slide" style="background: linear-gradient(135deg, rgba(0,0,0,0.9), rgba(0,102,177,0.3)), url('https://images.unsplash.com/photo-1555215695-3004980ad54e?w=1920') center/cover;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="banner-content">
                        <span class="badge bg-warning text-dark mb-3">SUV CAO CẤP</span>
                        <h1 class="banner-title reveal">BMW<br><span class="text-gradient">X7 xDrive40i</span></h1>
                        <p class="banner-subtitle">SUV 7 chỗ sang trọng nhất<br>Công nghệ hiện đại, tiện nghi đỉnh cao</p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="index.php?page=danhmuc" class="btn btn-primary btn-glow">Khám phá ngay</a>
                            <a href="index.php?page=testdrive" class="btn btn-outline">Lái thử miễn phí</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Slider Controls -->
    <div class="slider-dots">
        <span class="slider-dot active" data-slide="0"></span>
        <span class="slider-dot" data-slide="1"></span>
    </div>
    
    <button class="slider-nav prev" id="slider-prev"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-nav next" id="slider-next"><i class="fas fa-chevron-right"></i></button>
</section>

<!-- Featured Vehicles -->
<section class="section bg-black">
    <div class="container">
        <div class="section-header reveal">
            <h2 class="section-title">Xe nổi bật <span>BMW</span></h2>
            <p class="section-subtitle">Khám phá bộ sưu tập xe BMW cao cấp với nhiều ưu đãi hấp dẫn</p>
        </div>
        
        <div class="row g-4" id="vehicle-grid">
            <?php
            $featured_xe = $xe->getFeatured(6);
            foreach($featured_xe as $x): 
                $image = $xe->getPrimaryImage($x['vehicle_id']);
            ?>
            <div class="col-md-6 col-lg-4 vehicle-card reveal">
                <div class="card h-100">
                    <div class="card-image">
                        <img src="<?php echo $image['image_url'] ?? 'https://images.unsplash.com/photo-1617788138017-80a406023947?w=600'; ?>" alt="<?php echo $x['vehicle_name']; ?>">
                        <?php if($x['is_new']): ?>
                        <span class="card-badge">MỚI</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title"><?php echo $x['vehicle_name']; ?></h3>
                        <p class="text-muted small mb-2">Mã xe: <?php echo $x['vehicle_code']; ?> | <?php echo $x['model_year']; ?></p>
                        <p class="card-price">
                            <?php if($x['promotional_price']): ?>
                            <span class="text-decoration-line-through text-muted"><?php echo number_format($x['price'], 0, ',', '.'); ?> ₫</span>
                            <br>
                            <?php echo number_format($x['promotional_price'], 0, ',', '.'); ?> ₫
                            <?php else: ?>
                            <?php echo number_format($x['price'], 0, ',', '.'); ?> ₫
                            <?php endif; ?>
                        </p>
                        <div class="card-meta">
                            <span><i class="fas fa-tachometer-alt"></i> <?php echo $x['horsepower']; ?></span>
                            <span><i class="fas fa-car"></i> <?php echo $x['transmission']; ?></span>
                            <span><i class="fas fa-gas-pump"></i> <?php echo $x['fuel_type']; ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <div class="d-flex gap-2">
                            <a href="index.php?page=chitietxe&id=<?php echo $x['vehicle_id']; ?>" class="btn btn-outline flex-grow-1"><i class="fas fa-eye"></i> Xem nhanh</a>
                            <button class="btn btn-primary flex-grow-1 add-to-cart-btn" data-id="<?php echo $x['vehicle_id']; ?>"><i class="fas fa-cart-plus"></i> Mua ngay</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5 reveal">
            <a href="index.php?page=danhmuc" class="btn btn-outline btn-lg">
                <i class="fas fa-arrow-right"></i> Xem tất cả xe BMW
            </a>
        </div>
    </div>
</section>

<!-- Services -->
<section class="section bg-black">
    <div class="container">
        <div class="section-header reveal">
            <h2 class="section-title">Dịch vụ <span>BMW</span></h2>
            <p class="section-subtitle">Trải nghiệm dịch vụ chuyên nghiệp chuẩn BMW</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4 reveal">
                <div class="service-card text-center p-4">
                    <div class="service-icon mb-4">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3>Lái thử miễn phí</h3>
                    <p>Trải nghiệm cảm giác lái BMW hoàn toàn miễn phí tại showroom hoặc địa điểm theo yêu cầu.</p>
                    <a href="index.php?page=testdrive" class="btn btn-outline mt-3">Đăng ký ngay</a>
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay: 100ms;">
                <div class="service-card text-center p-4">
                    <div class="service-icon mb-4">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Bảo hành 5 năm</h3>
                    <p>Chế độ bảo hành chính hãng 5 năm hoặc 200.000 km, hỗ trợ khách hàng 24/7.</p>
                    <a href="#" class="btn btn-outline mt-3">Tìm hiểu thêm</a>
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay: 200ms;">
                <div class="service-card text-center p-4">
                    <div class="service-icon mb-4">
                        <i class="fas fa-percent"></i>
                    </div>
                    <h3>Trả góp ưu đãi</h3>
                    <p>Lãi suất ưu đãi chỉ từ 5.99%/năm, hỗ trợ trả góp đến 90% giá trị xe.</p>
                    <a href="#" class="btn btn-outline mt-3">Tính toán ngay</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="section" style="background: linear-gradient(135deg, var(--bmw-blue), #004A82);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h2 class="reveal">Liên hệ ngay để nhận tư vấn miễn phí!</h2>
                <p class="reveal">Đội ngũ chuyên gia BMW sẵn sàng hỗ trợ bạn 24/7</p>
            </div>
            <div class="col-lg-5">
                <form id="contact-form" class="reveal" method="POST" action="index.php?page=lienhe">
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Họ và tên" required name="full_name">
                    </div>
                    <div class="mb-3">
                        <input type="tel" class="form-control" placeholder="Số điện thoại" required name="phone">
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email">
                    </div>
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="fas fa-paper-plane"></i> Gửi yêu cầu
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.service-card {
    background: var(--dark);
    border-radius: 16px;
    transition: all 0.3s ease;
}
.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}
.service-icon {
    font-size: 48px;
    color: var(--bmw-blue);
}
</style>