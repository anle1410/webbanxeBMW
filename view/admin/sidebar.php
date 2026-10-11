<?php
// Admin sidebar chung - hiển thị các liên kết quản trị và badge tin nhắn chưa đọc
$msgModel = new MessageModel();
$unread = $msgModel->getUnreadCount();
?>
<div class="admin-sidebar p-3" style="min-height:100%;">
    <h5 class="text-white">Menu Admin</h5>
    <ul class="nav flex-column mt-3">
        <li class="nav-item mb-2"><a class="btn btn-dark w-100 text-start" href="index.php?page=admin">Dashboard</a></li>
        <li class="nav-item mb-2"><a class="btn btn-dark w-100 text-start" href="index.php?page=admin_users">Users</a></li>
        <li class="nav-item mb-2"><a class="btn btn-dark w-100 text-start" href="index.php?page=admin_vehicles">Xe</a></li>
        <li class="nav-item mb-2"><a class="btn btn-dark w-100 text-start" href="index.php?page=admin_orders">Đơn hàng</a></li>
        <li class="nav-item mb-2"><a class="btn btn-dark w-100 text-start" href="index.php?page=admin_testdrive">Lái thử</a></li>
        <li class="nav-item mb-2 position-relative">
            <a class="btn btn-dark w-100 text-start" href="index.php?page=admin_messages">Tin nhắn</a>
            <?php if ($unread > 0): ?>
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill"><?php echo $unread; ?></span>
            <?php endif; ?>
        </li>
    </ul>
</div>
