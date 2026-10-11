<?php
// Admin - Manage Vehicles

// Get all vehicles
$all_vehicles = $xe->getAllAdmin();
?>

<div class="admin-content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-3">
                <?php include 'view/admin/sidebar.php'; ?>
            </div>
            <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Quản lý Xe</h2>
            <a href="index.php?page=admin" class="btn btn-secondary">← Back</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hình ảnh</th>
                            <th>Tên xe</th>
                            <th>Mã xe</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Nổi bật</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($all_vehicles as $v): ?>
                        <?php $img = $xe->getPrimaryImage($v['vehicle_id']); ?>
                        <tr>
                            <td><?php echo $v['vehicle_id']; ?></td>
                            <td>
                                <?php if($img): ?>
                                    <img src="<?php echo $img['image_url']; ?>" width="80" style="object-fit:cover; border-radius:5px;">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $v['vehicle_name']; ?></td>
                            <td><?php echo $v['vehicle_code']; ?></td>
                            <td><?php echo $v['category_name']; ?></td>
                            <td><?php echo number_format($v['price'], 0, ',', '.'); ?> ₫</td>
                            <td>
                                <?php 
                                $status_class = [
                                    'available' => 'success',
                                    'sold' => 'danger',
                                    'reserved' => 'warning',
                                    'upcoming' => 'info'
                                ];
                                ?>
                                <span class="badge bg-<?php echo $status_class[$v['status']] ?? 'secondary'; ?>">
                                    <?php echo $v['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if($v['is_featured']): ?>
                                    <span class="badge bg-warning">Nổi bật</span>
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