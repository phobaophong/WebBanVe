<?php
require_once 'config/database.php';

// Cấu hình tài khoản Admin muốn tạo
$username = 'admin';
$password = '123456';
$fullname = 'Quản Trị Viên Hệ Thống';
$email = 'admin@vebongda.com';
$phone = '0888888888';

// Mã hóa mật khẩu chuẩn Bcrypt
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Kiểm tra xem đã có admin chưa để tránh lỗi trùng lặp
    $check = $conn->prepare("SELECT id FROM tbl_nguoidung WHERE ten_dang_nhap = :user");
    $check->execute(['user' => $username]);
    
    if ($check->rowCount() > 0) {
        echo "<h2 style='color: orange;'>⚠️ Tài khoản '$username' đã tồn tại trong hệ thống!</h2>";
        echo "<a href='pages/login.php'>Quay lại trang Đăng nhập</a>";
    } else {
        // Thực hiện Insert vào Database với vai_tro = 'admin'
        $sql = "INSERT INTO tbl_nguoidung (ten_dang_nhap, mat_khau, ho_ten, email, sdt, vai_tro) 
                VALUES (:user, :pass, :name, :email, :phone, 'admin')";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'user' => $username,
            'pass' => $hashed_password,
            'name' => $fullname,
            'email' => $email,
            'phone' => $phone
        ]);
        
        echo "<h2 style='color: green;'>✅ Tạo tài khoản Admin thành công!</h2>";
        echo "<ul>";
        echo "<li>Tên đăng nhập: <b>$username</b></li>";
        echo "<li>Mật khẩu: <b>$password</b></li>";
        echo "</ul>";
        echo "<a href='pages/login.php'>👉 Bấm vào đây để Đăng nhập ngay</a>";
    }
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Có lỗi xảy ra: " . $e->getMessage() . "</h2>";
}
?>