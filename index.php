<?php
// Entry point chính của ứng dụng WebBMW
// - Quản lý router cho các trang front-end
// - Xử lý yêu cầu POST: đăng nhập, đăng ký, cập nhật giỏ hàng, xóa sản phẩm, thanh toán
// - Kết nối với model và gửi dữ liệu tới views
// - Hỗ trợ AJAX cho hành động thêm giỏ hàng nhanh

// Nạp cấu hình kết nối CSDL
require_once 'config/database.php';

// Lấy tham số page và action từ URL để điều hướng
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Lấy session_id hiện tại hoặc khởi tạo session_id mới
// Session ID dùng để lưu giỏ hàng cho khách vãng lai trước khi đăng nhập
$session_id = isset($_SESSION['session_id']) ? $_SESSION['session_id'] : session_id();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Tiêu đề trang chung (nếu cần thêm sau này)
$page_title = '';

// Load tất cả model cần dùng trong ứng dụng
require_once 'model/xe_data.php';
require_once 'model/user_data.php';
require_once 'model/cart_data.php';
require_once 'model/order_data.php';
require_once 'model/testdrive_data.php';
require_once 'model/message_data.php';

// Ensure cart_count is always available for header rendering
$cart_model = new Cart();
$cart_count = $cart_model->getCount($session_id, $user_id);

// Xử lý AJAX request từ client, ví dụ sự kiện thêm vào giỏ hàng nhanh
if ($page == 'ajax') {
    header('Content-Type: application/json');
    
    if ($action == 'add_cart') {
        // Nhận vehicle_id từ query string
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        $cart_model = new Cart();
        $result = $cart_model->addItem($id, 1, $session_id, $user_id);
        
        // Trả về JSON gồm trạng thái và số lượng sản phẩm trong giỏ
        echo json_encode([
            'success' => $result,
            'cart_count' => $cart_model->getCount($session_id, $user_id)
        ]);
        exit();
    }
    
    echo json_encode(['success' => false]);
    exit();
}

// Xử lý các form gửi bằng phương thức POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Xử lý form đăng nhập
    if (isset($_POST['login'])) {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        // Gọi hàm login trong model user
        $result = $user->login($email, $password);
        
        if ($result['success']) {
            // Lưu thông tin người dùng vào session
            $user_data = $result['user'];
            $_SESSION['user_id'] = $user_data['user_id'];
            $_SESSION['user_name'] = $user_data['full_name'];
            $_SESSION['user_role'] = $user_data['role'];
            
            // Liên kết giỏ hàng session trước đó với user_id sau khi đăng nhập
            $cart_model = new Cart();
            $cart_model->updateUserId($session_id, $user_data['user_id']);

            // Xử lý chuyển hướng về trang ban đầu nếu có redirect_url
            $redirect_url = '';
            if (!empty($_POST['redirect_url'])) {
                $requested = trim($_POST['redirect_url']);
                if (strpos($requested, 'index.php') !== false || strpos($requested, '/') === 0) {
                    $redirect_url = $requested;
                }
            }

            if ($redirect_url) {
                header('Location: ' . $redirect_url);
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            // Nếu cần xác minh email, chuyển hướng sang trang login với thông báo
            if (isset($result['needs_verification']) && $result['needs_verification']) {
                header('Location: index.php?page=login&verify_email=' . urlencode($result['email']));
                exit();
            }
            $login_error = $result['error'];
        }
    }
    
    // Xử lý form đăng ký người dùng mới
    // Gọi model user->register để tạo tài khoản, sau đó gửi email xác minh nếu có mailer
    if (isset($_POST['register'])) {
        $data = [
            'full_name' => trim($_POST['full_name']),
            'email' => trim($_POST['email']),
            'phone' => trim($_POST['phone']),
            'password' => $_POST['password'],
            'address' => trim($_POST['address'])
        ];
        
        $result = $user->register($data);
        
        if ($result['success']) {
            // Try to send verification email using lib/mailer.php (PHPMailer if installed)
            $verification_link = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . dirname($_SERVER['PHP_SELF']) . "/index.php?page=verify&token=" . $result['verification_token'];

            $sent = false;
            if (file_exists(__DIR__ . '/lib/mailer.php')) {
                require_once __DIR__ . '/lib/mailer.php';
                // $mailer is created by lib/mailer.php
                $name = $data['full_name'] ?: $data['email'];
                try {
                    $sent = $mailer->sendVerification($data['email'], $name, $verification_link);
                } catch (Exception $e) {
                    error_log('Mailer error: ' . $e->getMessage());
                    $sent = false;
                }
            }

            if ($sent) {
                $register_success = "Đăng ký thành công! Vui lòng kiểm tra email để xác minh tài khoản.";
            } else {
                $register_success = "Đăng ký thành công! Vui lòng xác minh email để đăng nhập.<br>";
                $register_success .= "<small>Link xác minh (demo): <a href='$verification_link'>$verification_link</a></small>";
            }

            include 'view/layout/header.php';
            include 'view/pages/register.php';
            include 'view/layout/footer.php';
            exit();
        } else {
            $register_error = $result['error'];
        }
    }
    
    // Xử lý gửi lại email xác minh
    if (isset($_POST['resend_verification'])) {
        $email = trim($_POST['resend_email']);
        $result = $user->resendVerification($email);
        
        if ($result['success']) {
            $verification_link = "http://localhost/WebBMW/index.php?page=verify&token=" . $result['verification_token'];
            
            $login_success = "Đã gửi lại email xác minh!<br>";
            $login_success .= "<small>Link xác minh (demo): <a href='$verification_link'>$verification_link</a></small>";
            
            include 'view/layout/header.php';
            include 'view/pages/login.php';
            include 'view/layout/footer.php';
            exit();
        } else {
            $login_error = $result['error'];
        }
    }

        // Xử lý form liên hệ (trang lienhe)
        if (isset($_POST['send_contact'])) {
            $msg = new MessageModel();
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'subject' => trim($_POST['subject'] ?? ''),
                'message' => trim($_POST['message'] ?? '')
            ];

            if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
                $contact_error = 'Vui lòng điền đầy đủ họ tên, email và nội dung.';
            } else {
                $saved = $msg->create($data);
                if ($saved) {
                    $contact_success = 'Cảm ơn bạn! Tin nhắn đã được gửi, chúng tôi sẽ liên hệ sớm.';
                } else {
                    $contact_error = 'Gửi tin nhắn thất bại. Vui lòng thử lại sau.';
                }
            }
        }

    // Cập nhật thông tin cá nhân của người dùng
    // Bao gồm cập nhật tên, điện thoại, địa chỉ và upload avatar nếu có
    if (isset($_POST['update_profile'])) {
        $profile_data = [
            'full_name' => trim($_POST['full_name']),
            'phone'     => trim($_POST['phone']),
            'address'   => trim($_POST['address'])
        ];

        if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatar_dir = __DIR__ . '/uploads/avatars/';
            if (!is_dir($avatar_dir)) {
                mkdir($avatar_dir, 0755, true);
            }

            $file_ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_ext, $allowed)) {
                $filename = 'avatar_' . $user_id . '_' . time() . '.' . $file_ext;
                $target = $avatar_dir . $filename;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
                    $profile_data['avatar'] = 'uploads/avatars/' . $filename;
                } else {
                    $profile_error = 'Không thể tải ảnh avatar lên. Vui lòng thử lại.';
                }
            } else {
                $profile_error = 'Định dạng ảnh không hợp lệ. Vui lòng chọn JPG, PNG hoặc GIF.';
            }
        }

        if (!isset($profile_error)) {
            $updated = $user->update($user_id, $profile_data);
            if ($updated) {
                $profile_success = 'Cập nhật thông tin cá nhân thành công.';
            } else {
                $profile_error = 'Cập nhật thông tin thất bại. Vui lòng thử lại.';
            }
        }
    }

    // Xử lý thêm sản phẩm vào giỏ hàng từ trang chi tiết xe
    if (isset($_POST['add_to_cart'])) {
        $vehicle_id = isset($_POST['vehicle_id']) ? intval($_POST['vehicle_id']) : 0;
        if (!$user_id) {
            // Nếu chưa đăng nhập, chuyển sang trang login và nhớ trang trả về
            $redirect_url = 'index.php?page=chitietxe&id=' . intval($vehicle_id);
            header('Location: index.php?page=login&redirect=' . urlencode($redirect_url));
            exit();
        }

        $cart_model = new Cart();
        $cart_model->addItem($vehicle_id, 1, $session_id, $user_id);
        header('Location: index.php?page=giohang');
        exit();
    }

    // Xử lý cập nhật số lượng trong giỏ hàng
    if (isset($_POST['update_cart']) && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
        $cart_model = new Cart();
        foreach ($_POST['cart_id'] as $index => $cart_id) {
            $cart_id = intval($cart_id);
            $quantity = isset($_POST['quantity'][$index]) ? intval($_POST['quantity'][$index]) : 1;
            if ($quantity <= 0) {
                // Nếu số lượng nhập bằng 0 hoặc âm, xóa sản phẩm khỏi giỏ hàng
                $cart_model->removeItem($cart_id);
            } else {
                $cart_model->updateQuantity($cart_id, $quantity);
            }
        }
        header('Location: index.php?page=giohang');
        exit();
    }

    // Xử lý xóa sản phẩm khỏi giỏ hàng theo cart_id
    if (isset($_POST['remove_cart'])) {
        $cart_model = new Cart();
        $cart_model->removeItem(intval($_POST['remove_cart']));
        header('Location: index.php?page=giohang');
        exit();
    }
    
}

// Verify email when user clicks the link
if ($page == 'verify' && isset($_GET['token'])) {
    $token = $_GET['token'];
    if ($user->verifyEmail($token)) {
        header('Location: index.php?page=login&verified=1');
        exit();
    } else {
        header('Location: index.php?page=login&verify_error=1');
        exit();
    }
}

// Checkout and test drive booking only on POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Checkout
    if (isset($_POST['checkout'])) {
        if (!$user_id) {
            header('Location: index.php?page=login');
            exit();
        }

        $cart_model = new Cart();
        $order_items = [];
        $subtotal = 0;
        $direct_checkout = false;

        if (!empty($_POST['vehicle_id'])) {
            $direct_checkout = true;
            $vehicle_id = intval($_POST['vehicle_id']);
            $quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;
            $vehicle = $xe->getById($vehicle_id);

            if (!$vehicle) {
                echo '<div class="container py-5"><div class="alert alert-danger">Xe không tồn tại!</div></div>';
                exit();
            }

            $unit_price = !empty($vehicle['promotional_price']) ? $vehicle['promotional_price'] : $vehicle['price'];
            $subtotal = $unit_price * $quantity;
            $order_items[] = [
                'vehicle_id' => $vehicle['vehicle_id'],
                'quantity' => $quantity,
                'unit_price' => $unit_price,
                'subtotal' => $subtotal
            ];
        } else {
            $items = $cart_model->getItems($session_id, $user_id);
            if (empty($items)) {
                header('Location: index.php?page=giohang');
                exit();
            }

            foreach ($items as $item) {
                $price = $item['promotional_price'] ? $item['promotional_price'] : $item['price'];
                $subtotal += $price * $item['quantity'];
                $order_items[] = [
                    'vehicle_id' => $item['vehicle_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $price,
                    'subtotal' => $price * $item['quantity']
                ];
            }
        }

        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;

        $order_data = [
            'user_id' => $user_id,
            'customer_name' => $_POST['customer_name'],
            'customer_phone' => $_POST['customer_phone'],
            'customer_email' => $_POST['customer_email'],
            'shipping_address' => $_POST['shipping_address'],
            'order_notes' => $_POST['order_notes'],
            'payment_method' => $_POST['payment_method'],
            'payment_status' => 'unpaid',
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'shipping_fee' => 0,
            'discount_amount' => 0,
            'total_amount' => $total
        ];

        $order_model = new Order();
        $order_number = $order_model->create($order_data, $order_items);

        if ($order_number) {
            if (!$direct_checkout) {
                $cart_model->clearCart($session_id, $user_id);
            }
            header('Location: index.php?page=order_pending&order_number=' . urlencode($order_number));
            exit();
        }
    }

    // Test drive booking
    if (isset($_POST['booking_testdrive'])) {
        $testdrive_model = new TestDrive();
        $testdrive_id = $testdrive_model->create([
            'user_id' => $user_id,
            'vehicle_id' => isset($_POST['vehicle_id']) ? intval($_POST['vehicle_id']) : 0,
            'full_name' => $_POST['full_name'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'desired_date' => $_POST['desired_date'],
            'desired_time' => $_POST['desired_time'],
            'pickup_location' => $_POST['pickup_location']
        ]);
        
        if ($testdrive_id) {
            header('Location: index.php?page=testdrive&success=1');
            exit();
        }
    }
}

// Logout
if ($page == 'logout') {
    session_destroy();
    header('Location: index.php');
    exit();
}

// Include header
include 'view/layout/header.php';

// Render page content
switch($page) {
    case 'home':
        $page_title = 'Trang chủ';
        include 'view/pages/trangchu.php';
        break;
        
    case 'danhmuc':
        $page_title = 'Danh sách xe';
        $vehicles = $xe->getAll();
        include 'view/pages/danhmuc.php';
        break;
        
    case 'chitietxe':
        $xe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $xe_detail = $xe->getById($xe_id);
        if (!$xe_detail) {
            echo '<div class="container py-5"><div class="alert alert-danger">Xe không tồn tại!</div></div>';
            break;
        }
        $page_title = $xe_detail['vehicle_name'];
        $xe_images = $xe->getImages($xe_id);
        $related_xe = $xe->getRelated($xe_id, $xe_detail['category_id']);
        include 'view/pages/chitietxe.php';
        break;
        
    case 'giohang':
        $page_title = 'Giỏ hàng';
        $cart_items = $cart_model->getItems($session_id, $user_id);
        $cart_total = $cart_model->getTotal($session_id, $user_id);
        include 'view/pages/giohang.php';
        break;
        
    case 'thanhtoan':
        $page_title = 'Thanh toán';
        if (!$user_id) {
            $redirect_url = 'index.php?page=thanhtoan';
            if (!empty($_GET['vehicle_id'])) {
                $redirect_url .= '&vehicle_id=' . intval($_GET['vehicle_id']);
            }
            header('Location: index.php?page=login&redirect=' . urlencode($redirect_url));
            exit();
        }

        $cart_items = [];
        $cart_total = ['subtotal' => 0, 'tax' => 0, 'total' => 0];
        $direct_checkout = false;

        if (!empty($_GET['vehicle_id'])) {
            $direct_checkout = true;
            $vehicle_id = intval($_GET['vehicle_id']);
            $vehicle = $xe->getById($vehicle_id);

            if (!$vehicle) {
                echo '<div class="container py-5"><div class="alert alert-danger">Xe không tồn tại!</div></div>';
                break;
            }

            $quantity = isset($_GET['qty']) ? max(1, intval($_GET['qty'])) : 1;
            $unit_price = !empty($vehicle['promotional_price']) ? $vehicle['promotional_price'] : $vehicle['price'];
            $subtotal = $unit_price * $quantity;
            $tax = $subtotal * 0.1;
            $total = $subtotal + $tax;

            $primary_image = $xe->getPrimaryImage($vehicle_id);
            $cart_items[] = [
                'vehicle_id' => $vehicle['vehicle_id'],
                'vehicle_name' => $vehicle['vehicle_name'],
                'vehicle_code' => $vehicle['vehicle_code'],
                'quantity' => $quantity,
                'price' => $vehicle['price'],
                'promotional_price' => $vehicle['promotional_price'],
                'image_url' => $primary_image['image_url'] ?? null,
                'unit_price' => $unit_price,
                'subtotal' => $subtotal
            ];

            $cart_total = [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total
            ];
        } else {
            $cart_items = $cart_model->getItems($session_id, $user_id);
            if (empty($cart_items)) {
                header('Location: index.php?page=giohang');
                exit();
            }
            $total_data = $cart_model->getTotal($session_id, $user_id);
            $cart_total = [
                'subtotal' => $total_data['subtotal'] ?? 0,
                'tax' => $total_data['tax'] ?? 0,
                'total' => $total_data['total'] ?? 0
            ];
        }

        include 'view/pages/thanhtoan.php';
        break;
        
    case 'donhang':
        $page_title = 'Đơn hàng';
        if (!$user_id) {
            header('Location: index.php?page=login');
            exit();
        }
        $orders = $order->getByUser($user_id);
        include 'view/pages/donhang.php';
        break;

    case 'order_pending':
        $page_title = 'Chờ duyệt đơn hàng';
        if (!$user_id) {
            header('Location: index.php?page=login');
            exit();
        }
        $order_number = isset($_GET['order_number']) ? trim($_GET['order_number']) : '';
        $pending_order = null;
        if ($order_number) {
            $pending_order = $order->getByNumber($order_number);
        }
        include 'view/pages/order_pending.php';
        break;
        
    case 'testdrive':
        $page_title = 'Lái thử';
        include 'view/pages/testdrive.php';
        break;
        
    case 'login':
        $page_title = 'Đăng nhập';
        include 'view/pages/login.php';
        break;
        
    case 'register':
        $page_title = 'Đăng ký';
        include 'view/pages/register.php';
        break;
        
    case 'profile':
        $page_title = 'Tài khoản';
        if (!$user_id) {
            header('Location: index.php?page=login');
            exit();
        }
        include 'view/pages/profile.php';
        break;
        
    case 'admin':
    case 'admin_dashboard':
        $page_title = 'Quản trị';
        if (!$user_id || $_SESSION['user_role'] != 'admin') {
            header('Location: index.php');
            exit();
        }
        include 'view/admin/dashboard.php';
        break;
        
    case 'admin_users':
        $page_title = 'Quản lý users';
        if (!$user_id || $_SESSION['user_role'] != 'admin') {
            header('Location: index.php');
            exit();
        }
        include 'view/admin/users.php';
        break;
        
    case 'admin_vehicles':
        $page_title = 'Quản lý xe';
        if (!$user_id || $_SESSION['user_role'] != 'admin') {
            header('Location: index.php');
            exit();
        }
        include 'view/admin/vehicles.php';
        break;
        
    case 'admin_orders':
        $page_title = 'Quản lý đơn hàng';
        if (!$user_id || $_SESSION['user_role'] != 'admin') {
            header('Location: index.php');
            exit();
        }
        include 'view/admin/orders.php';
        break;
        
    case 'admin_testdrive':
        $page_title = 'Quản lý lái thử';
        if (!$user_id || $_SESSION['user_role'] != 'admin') {
            header('Location: index.php');
            exit();
        }
        include 'view/admin/testdrive.php';
        break;
        
    case 'admin_messages':
        $page_title = 'Quản lý tin nhắn';
        if (!$user_id || $_SESSION['user_role'] != 'admin') {
            header('Location: index.php');
            exit();
        }
        // Thao tác mark as read hoặc delete từ admin
        $messageModel = new MessageModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['mark_read_id'])) {
                $messageModel->markAsRead(intval($_POST['mark_read_id']));
                header('Location: index.php?page=admin_messages');
                exit();
            }
            if (isset($_POST['delete_msg_id'])) {
                $messageModel->delete(intval($_POST['delete_msg_id']));
                header('Location: index.php?page=admin_messages');
                exit();
            }
        }
        $messages = $messageModel->getAll();
        include 'view/admin/messages.php';
        break;
        
    case 'lienhe':
        $page_title = 'Liên hệ';
        include 'view/pages/lienhe.php';
        break;
        
    default:
        $page_title = 'Trang chủ';
        include 'view/pages/trangchu.php';
        break;
}

// Include footer
include 'view/layout/footer.php';