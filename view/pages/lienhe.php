<?php
// View: Lien he
?>

<div class="container py-5 mt-5">
    <h2 class="mb-4">Liên hệ</h2>
    
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gửi tin nhắn</h5>
                    <?php if (isset($contact_success)): ?>
                        <div class="alert alert-success"><?php echo $contact_success; ?></div>
                    <?php endif; ?>
                    <?php if (isset($contact_error)): ?>
                        <div class="alert alert-danger"><?php echo $contact_error; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="index.php?page=lienhe">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Chủ đề</label>
                            <input type="text" name="subject" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" name="send_contact" class="btn btn-primary">Gửi tin nhắn</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">BMW Ha Noi</h5>
                    <p><i class="fas fa-map-marker-alt"></i> So 1 Dai lo Thang Long, Cau Giay, Ha Noi</p>
                    <p><i class="fas fa-phone"></i> 024 1234 5678</p>
                    <p><i class="fas fa-envelope"></i> hanoi@bmw.com.vn</p>
                    <p><i class="fas fa-clock"></i> 08:00 - 20:00</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">BMW TP.HCM</h5>
                    <p><i class="fas fa-map-marker-alt"></i> So 188 Nguyen Van Troi, Phu Nhuan, TP.HCM</p>
                    <p><i class="fas fa-phone"></i> 028 1234 5678</p>
                    <p><i class="fas fa-envelope"></i> hcmc@bmw.com.vn</p>
                    <p><i class="fas fa-clock"></i> 08:00 - 20:00</p>
                </div>
            </div>
        </div>
    </div>
</div>