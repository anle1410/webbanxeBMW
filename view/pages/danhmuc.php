<?php
// View: Danh muc xe
?>

<div class="container py-5 mt-5">
    <h2 class="mb-4">Danh sách xe BMW</h2>
    
    <!-- Filter -->
    <div class="filter-bar d-flex flex-wrap gap-3 mb-4">
        <select id="category-filter" class="form-control" style="width: auto;">
            <option value="">Tất cả dòng xe</option>
            <option value="series-m">BMW Series M</option>
            <option value="x-series">BMW X Series</option>
            <option value="i-series">BMW i Series</option>
            <option value="sedan">BMW Sedan</option>
            <option value="coupe">BMW Coupe</option>
        </select>
        <input type="text" id="search-input" class="form-control" placeholder="Tìm kiếm xe..." style="width: 250px;">
    </div>
    
    <div class="row g-4" id="vehicle-grid">
        <?php if(!empty($vehicles)): ?>
            <?php foreach($vehicles as $x): ?>
                <?php $img = isset($xe) ? $xe->getPrimaryImage($x['vehicle_id']) : null; ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-image">
                            <img src="<?php echo $img['image_url'] ?? 'https://via.placeholder.com/600x400'; ?>" alt="<?php echo $x['vehicle_name']; ?>" loading="lazy">
                            <?php if($x['is_new']): ?>
                            <span class="card-badge">MỚI</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?php echo $x['vehicle_name']; ?></h3>
                            <p class="text-muted small">Mã: <?php echo $x['vehicle_code']; ?> | <?php echo $x['model_year']; ?></p>
                            <p class="card-price">
                                <?php if(!empty($x['promotional_price'])): ?>
                                    <span class="text-decoration-line-through"><?php echo number_format($x['price'], 0, ',', '.'); ?> ₫</span><br>
                                    <?php echo number_format($x['promotional_price'], 0, ',', '.'); ?> ₫
                                <?php else: ?>
                                    <?php echo number_format($x['price'], 0, ',', '.'); ?> ₫
                                <?php endif; ?>
                            </p>
                            <div class="card-meta">
                                <span><i class="fas fa-tachometer-alt"></i> <?php echo $x['horsepower']; ?></span>
                                <span><i class="fas fa-car"></i> <?php echo $x['transmission']; ?></span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="index.php?page=chitietxe&id=<?php echo $x['vehicle_id']; ?>" class="btn btn-primary w-100">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">Chưa có xe nào!</div>
            </div>
        <?php endif; ?>
    </div>
</div>