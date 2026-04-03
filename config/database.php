<?php
// Thông tin cấu hình cơ sở dữ liệu (Dành cho VertrigoServ)
$host = 'localhost';
$dbname = 'banve_worldcup'; // Tên database mà bạn đã tạo trong phpMyAdmin
$username = 'root';         // Tài khoản mặc định
$password = 'vertrigo';     // Mật khẩu mặc định của VertrigoServ

try {
    // Khởi tạo kết nối PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Thiết lập chế độ báo lỗi để dễ dàng gỡ lỗi (debug) khi code
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Nếu muốn test xem kết nối được chưa, bạn có thể bỏ dấu // ở dòng dưới đây:
    // echo "Kết nối CSDL banve_worldcup qua Vertrigo thành công!"; 

} catch(PDOException $e) {
    // Ngắt chương trình và báo lỗi màu đỏ nếu kết nối thất bại
    die("<h3 style='color:red;'>Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage() . "</h3>");
}
?>