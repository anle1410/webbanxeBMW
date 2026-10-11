<?php
// View: Test drive
$success = isset($_GET['success']) ? $_GET['success'] : 0;
$xe_model = new Xe();
$vehicles = $xe_model->getAll(50);
?>

<div class="container py-5 mt-5">
    <h2 class="mb-4">Đăng ký lái thử</h2>
    
    <?php if($success): ?>
    <div class="alert alert-success">
        <h4>Đăng ký thành công!</h4>
        <p>Chúng tôi sẽ liên hệ với bạn sớm để xác nhận lịch lái thử.</p>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Chọn xe *</label>
                            <select name="vehicle_id" class="form-control" required>
                                <option value="">-- Chọn xe --</option>
                                <?php foreach($vehicles as $v): ?>
                                <option value="<?php echo $v['vehicle_id']; ?>"><?php echo $v['vehicle_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên *</label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại *</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày muốn lái thử *</label>
                                <input type="date" name="desired_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giờ *</label>
                                <input type="time" name="desired_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa điểm *</label>
                            <select name="pickup_location" class="form-control" required>
                                <option value="">-- Chọn showroom --</option>
                                <option value="BMW Ha Noi">BMW Ha Noi - Cau Giay</option>
                                <option value="BMW TP.HCM">BMW TP.HCM - Phu Nhuan</option>
                            </select>
                        </div>
                        <button type="submit" name="booking_testdrive" class="btn btn-primary">Đăng ký lái thử</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin lái thử</h5>
                </div>
                <div class="card-body">
                    <p><strong>Thời gian:</strong> 30-45 phút</p>
                    <p><strong>Địa điểm:</strong> Showroom BMW gần bạn</p>
                    <p><strong>Lưu ý:</strong></p>
                    <ul>
                        <li>Mang theo GPLX</li>
                        <li>Đủ 18 tuổi trở lên</li>
                        <li>Liên hệ trước để đặt lịch</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>