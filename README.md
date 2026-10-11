<<<<<<< HEAD
# BMW Showroom Vietnam

<p align="center">
  <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=1600&q=80" alt="BMW Showroom" width="100%" />
</p>

<div align="center">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5" />
  <img src="https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript" />
</div>

<h3 align="center"><strong>Website showroom xe BMW hiện đại, đầy đủ tính năng, sang trọng và chuyên nghiệp</strong></h3>

## Giới thiệu

BMW Showroom Vietnam là một dự án website thương mại điện tử dành cho showroom ô tô BMW, mang phong cách tối giản nhưng đẳng cấp, thể hiện giá trị thương hiệu xe sang và hiệu suất cao. Dự án được xây dựng bằng PHP theo mô hình MVC, kết hợp Bootstrap 5, JavaScript và MySQL để tạo ra một trang web bán xe chuyên nghiệp, có tính trải nghiệm cao và dễ mở rộng.

Website này không chỉ là nơi hiển thị các mẫu xe BMW mà còn tích hợp đầy đủ các chức năng như đăng ký tài khoản, đăng nhập, xác thực email, giỏ hàng, thanh toán, đặt lịch lái thử, theo dõi đơn hàng, quản lý tin nhắn khách hàng và hệ thống quản trị admin.

---

## Tính năng nổi bật

### 1. Trang chủ sang trọng
- Hero slider ấn tượng với hình ảnh xe BMW chất lượng cao
- Xe nổi bật được hiển thị theo phong cách showroom cao cấp
- Chức năng CTA (Khám phá ngay, Lái thử miễn phí)
- Thiết kế tối màu, hiện đại theo chủ đề BMW

### 2. Danh sách xe BMW
- Hiển thị danh sách xe theo từng mẫu, dòng xe và phiên bản
- Thông tin về giá, mã xe, năm sản xuất, ưu đãi
- Hình ảnh xe đẹp mắt, rõ ràng và chuyên nghiệp
- Hỗ trợ xem nhanh và đưa vào giỏ hàng ngay từ trang danh mục

### 3. Chi tiết sản phẩm xe
- Hiển thị hình ảnh xe, thông số kỹ thuật chi tiết
- Mô tả động cơ, hộp số, nhiên liệu, công suất, mô-men xoắn
- Tốc độ tối đa, thời gian tăng tốc, trọng lượng, kích thước
- Thông tin bổ sung về tính năng nội thất và ngoại thất

### 4. Giỏ hàng và thanh toán
- Thêm xe vào giỏ hàng nhanh chóng
- Tăng/giảm số lượng sản phẩm
- Xóa sản phẩm khỏi giỏ hàng
- Tính toán tổng tiền tự động
- Chọn phương thức thanh toán như tiền mặt, chuyển khoản hoặc trả góp

### 5. Đặt lịch lái thử
- Chọn xe muốn thử
- Chọn ngày, giờ và địa điểm nhận xe
- Lưu thông tin vào hệ thống
- Admin có thể xác nhận hoặc hủy lịch thử

### 6. Đăng ký và đăng nhập
- Tạo tài khoản mới
- Mã hóa mật khẩu an toàn
- Xác minh email trước khi đăng nhập
- Quản lý lỗi đăng nhập và khóa tài khoản tạm thời nếu nhập sai nhiều lần

### 7. Tài khoản cá nhân
- Cập nhật thông tin cá nhân
- Thay đổi ảnh đại diện
- Xem lịch sử đơn hàng và lịch lái thử

### 8. Liên hệ và quản lý tin nhắn
- Form liên hệ khách hàng
- Gửi tin nhắn tới admin
- Theo dõi tin nhắn đã đọc/chưa đọc
- Quản lý thông tin phản hồi từ khách hàng

### 9. Quản trị Admin
- Dashboard thống kê tổng quan
- Quản lý người dùng
- Quản lý xe
- Quản lý đơn hàng
- Quản lý lịch thử lái
- Quản lý tin nhắn

---

## Công nghệ sử dụng

| Phần | Công nghệ |
|---|---|
| Backend | PHP 8.x, MVC |
| Frontend | HTML5, CSS3, Bootstrap 5 |
| JavaScript | ES6, AJAX |
| Database | MySQL |
| UI Library | Font Awesome |
| Fonts | Google Fonts |
| Mẫu thiết kế | Luxury showroom / premium automobile |

---

## Cấu trúc dự án

```text
WebBMW/
├─ admin/                         # Giao diện quản trị admin
├─ assets/                       # Hình ảnh, favicon, resource front-end
├─ config/                       # Cấu hình database và hệ thống
├─ controller/                   # Controller chính
├─ database/                     # File SQL schema và dữ liệu mẫu
├─ helpers/                      # Hàm hỗ trợ / utilities
├─ lib/                          # Thư viện bổ trợ
├─ model/                        # Model xử lý logic dữ liệu
├─ routes/                       # Định tuyến ứng dụng
├─ services/                     # Logic nghiệp vụ bổ sung
├─ uploads/                      # File upload người dùng
├─ view/                         # Giao diện người dùng
│  ├─ admin/                    # Trang quản trị
│  ├─ layout/                   # Header/Footer chung
│  └─ pages/                    # Trang người dùng
├─ composer.json                 # Thông tin dependency PHP
├─ composer.lock                 # Phiên bản lock của Composer
├─ index.php                     # Điểm vào chính của ứng dụng
├─ README.md                     # Tài liệu hướng dẫn dự án
├─ test_pdo.php                  # Test kết nối PDO
└─ vendor/                       # Thư viện Composer
```

---

## Các trang chính

### Trang khách hàng
- `index.php` – Trang chủ
- `index.php?page=danhmuc` – Danh sách xe
- `index.php?page=chitietxe&id=...` – Chi tiết xe
- `index.php?page=giohang` – Giỏ hàng
- `index.php?page=thanhtoan` – Thanh toán
- `index.php?page=testdrive` – Lái thử
- `index.php?page=lienhe` – Liên hệ
- `index.php?page=login` – Đăng nhập
- `index.php?page=register` – Đăng ký
- `index.php?page=profile` – Tài khoản cá nhân
- `index.php?page=donhang` – Đơn hàng

### Trang quản trị
- `index.php?page=admin` – Dashboard
- `index.php?page=admin_users` – Quản lý người dùng
- `index.php?page=admin_vehicles` – Quản lý xe
- `index.php?page=admin_orders` – Quản lý đơn hàng
- `index.php?page=admin_testdrive` – Quản lý lịch lái thử
- `index.php?page=admin_messages` – Quản lý tin nhắn

---

## Cơ sở dữ liệu

Dự án sử dụng MySQL với các bảng chính như:

- `users` – thông tin người dùng
- `roles` – vai trò người dùng
- `categories` – danh mục xe
- `vehicles` – thông tin chi tiết xe
- `vehicle_images` – hình ảnh xe
- `orders` – đơn hàng
- `order_items` – sản phẩm trong đơn hàng
- `test_drive` – lịch lái thử
- `messages` – tin nhắn liên hệ
- `login_attempts` – ghi nhận đăng nhập sai
- `email_verification` – xác minh email

File schema database được lưu trong thư mục:

```text
database/
├─ schema.sql
├─ sample_data.sql
├─ create_admin.sql
├─ create_messages.sql
├─ upgrade_users.sql
```

---

## Yêu cầu hệ thống

- PHP 8.2+
- MySQL 8.0+
- Apache / Nginx
- Trình duyệt hiện đại (Chrome, Edge, Firefox)
- Composer (nếu cần bổ sung thư viện)

---

## Hướng dẫn cài đặt

### Bước 1: Clone repository

```bash
git clone <repository-url>
cd WebBMW
```

### Bước 2: Cấu hình database

Mở file:

```php
config/database.php
```

Cập nhật thông tin Kết nối MySQL:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bmwdatabase');
define('DB_CHARSET', 'utf8mb4');
```

### Bước 3: Tạo database

```sql
CREATE DATABASE bmwdatabase;
USE bmwdatabase;
```

### Bước 4: Import file SQL

```bash
mysql -u root -p bmwdatabase < database/schema.sql
mysql -u root -p bmwdatabase < database/sample_data.sql
```

### Bước 5: Khởi chạy ứng dụng

#### Nếu dùng PHP built-in server:

```bash
cd /path/to/WebBMW
php -S 127.0.0.1:8000
```

Sau đó mở trong trình duyệt:

```text
http://127.0.0.1:8000/index.php
```

---

## Tài khoản demo

Sau khi import dữ liệu mẫu, bạn có thể tạo tài khoản mới hoặc đăng nhập bằng tài khoản admin nếu đã được setup trong database. Hệ thống hỗ trợ chức năng xác minh email và bảo mật bằng cách khóa tài khoản sau nhiều lần nhập sai.

---

## Quy trình kinh doanh của hệ thống

1. Khách hàng truy cập trang chủ và xem các mẫu xe BMW
2. Duyệt danh mục xe và xem chi tiết sản phẩm
3. Thêm xe vào giỏ hàng
4. Thanh toán hoặc đặt mua xe
5. Theo dõi đơn hàng trong tài khoản
6. Đăng ký lịch lái thử
7. Gửi liên hệ đến showroom
8. Admin xử lý đơn hàng, xác nhận thử lái và phản hồi khách hàng

---

## Đánh giá và điểm mạnh

### Điểm mạnh
- Giao diện hiện đại, trực quan và phù hợp thương hiệu BMW
- Hệ thống đầy đủ các chức năng cho website showroom ô tô
- Dễ nâng cấp và mở rộng thêm tính năng mới
- Chia tách rõ ràng giữa UI, controller và model
- Thiết kế phù hợp cho môi trường học tập và demo sản phẩm

### Hướng phát triển tiếp theo
- Tích hợp thanh toán online thật
- Gửi email tự động cho khách hàng
- Cập nhật bảo mật nâng cao
- Thêm chat hỗ trợ trực tiếp
- Tích hợp API cho quản lý kho hàng và CRM

---

## Lưu ý bảo mật

Để triển khai lên môi trường production, nên bổ sung:
- HTTPS
- Validate dữ liệu đầu vào chặt chẽ hơn
- Xử lý upload ảnh an toàn
- Phân quyền admin rõ ràng
- Ẩn thông tin nhạy cảm trong file cấu hình

---

## Tác giả

<div align="center">
  <h2><strong>Lê Phú An</strong></h2>
  <p>Developer • Designer • Builder of BMW Showroom Vietnam</p>
</div>

---

## Kết luận

BMW Showroom Vietnam là một dự án website showroom xe BMW chuyên nghiệp với giao diện cao cấp, hệ thống bán hàng, quản trị và trải nghiệm người dùng rõ ràng. Dự án này phù hợp cho mục đích học tập, demo, và có thể được mở rộng thành một nền tảng thương mại điện tử ô tô thực tế.

<p align="center">
  <strong>© 2026 BMW Showroom Vietnam</strong><br>
  <strong>Tác giả: Lê Phú An</strong>
</p>
=======
# webbanxe
bán xe của hảng BMW
>>>>>>> b50b74dc666995acb387e1b27b78be20b73fe17d
