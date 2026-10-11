<?php
// Admin: Danh sách tin nhắn liên hệ
// Yêu cầu: $_SESSION['user_role'] == 'admin'
?>
<div class="admin-content">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-3">
                <?php include 'view/admin/sidebar.php'; ?>
            </div>
            <div class="col-md-9">
                <div class="container py-5">
                    <h2 class="mb-4">Tin nhắn từ khách hàng</h2>

    <?php if (!empty($messages)): ?>
        <div class="list-group">
            <?php foreach ($messages as $m): ?>
                <div class="list-group-item d-flex justify-content-between align-items-start <?php echo $m['is_read'] ? '' : 'bg-light'; ?>">
                    <div>
                        <h6 class="mb-1"><?php echo htmlspecialchars($m['subject'] ?: '(Không có chủ đề)'); ?></h6>
                        <div class="small text-muted"><?php echo htmlspecialchars($m['name']); ?> — <?php echo htmlspecialchars($m['email']); ?> — <?php echo htmlspecialchars($m['phone']); ?> — <?php echo $m['created_at']; ?></div>
                        <p class="mt-2 mb-0"><?php echo nl2br(htmlspecialchars($m['message'])); ?></p>
                    </div>
                    <div class="text-end">
                        <?php if (!$m['is_read']): ?>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="mark_read_id" value="<?php echo intval($m['id']); ?>">
                                <button class="btn btn-sm btn-outline-primary" type="submit">Đánh dấu đã đọc</button>
                            </form>
                        <?php endif; ?>
                        <form method="POST" class="d-inline mt-2">
                            <input type="hidden" name="delete_msg_id" value="<?php echo intval($m['id']); ?>">
                            <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Xóa tin nhắn này?')">Xóa</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
                    <div class="alert alert-info">Không có tin nhắn nào.</div>
    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
