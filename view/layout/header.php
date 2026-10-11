<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - BMW' : 'BMW Showroom Vietnam'; ?></title>
    <meta name="description" content="BMW Showroom Vietnam - Đại lý xe BMW chính hãng">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="view/css/theme.css">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>
<body class="bg-black text-white <?php echo isset($body_class) ? $body_class : ''; ?>">
    
    <!-- Header -->
    <header class="header glass-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <a href="index.php" class="logo">BMW<span>.</span></a>
                </div>
                <div class="col-lg-6">
                    <nav class="nav-links d-flex gap-4">
                        <a href="index.php" class="nav-link <?php echo $page == 'home' ? 'active' : ''; ?>">Trang chủ</a>
                        <a href="index.php?page=danhmuc" class="nav-link <?php echo $page == 'danhmuc' ? 'active' : ''; ?>">Danh sách xe</a>
                        <a href="index.php?page=lienhe" class="nav-link <?php echo $page == 'lienhe' ? 'active' : ''; ?>">Liên hệ</a>
                    </nav>
                </div>
                <div class="col-lg-3 d-flex justify-content-end gap-3 align-items-center">
                    <a href="index.php?page=giohang" class="btn btn-outline btn-sm position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cart-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill"><?php echo $cart_count; ?></span>
                    </a>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                        <?php $messageModel = new MessageModel(); $unread = $messageModel->getUnreadCount(); ?>
                        <a href="index.php?page=admin_messages" class="btn btn-outline btn-sm position-relative ms-2">
                            <i class="fas fa-bell"></i>
                            <?php if ($unread > 0): ?>
                                <span id="msg-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill"><?php echo $unread; ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="index.php?page=profile">Tài khoản</a></li>
                                <li><a class="dropdown-item" href="index.php?page=donhang">Đơn hàng</a></li>
                                <?php if ($_SESSION['user_role'] == 'admin'): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="index.php?page=admin">Quản trị</a></li>
                                <li><a class="dropdown-item" href="index.php?page=admin_messages">Tin nhắn</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="index.php?page=logout">Đăng xuất</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="index.php?page=login" class="btn btn-primary btn-sm">
                            <i class="fas fa-user"></i> Đăng nhập
                        </a>
                    <?php endif; ?>
                    <button class="btn d-lg-none" id="hamburger-btn">
                        <i class="fas fa-bars text-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>