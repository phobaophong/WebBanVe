<?php
// BẮT BUỘC KHỞI TẠO SESSION TRƯỚC KHI LÀM BẤT CỨ ĐIỀU GÌ
session_start();

require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['txt_username']);
    $password = $_POST['txt_password'];

    try {
        // 1. Tìm tài khoản trong Database bằng Tên đăng nhập
        $sql = "SELECT id, ten_dang_nhap, mat_khau, ho_ten, vai_tro, so_du FROM tbl_nguoidung WHERE ten_dang_nhap = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Nếu tìm thấy user VÀ mật khẩu giải mã khớp nhau
        if ($user && password_verify($password, $user['mat_khau'])) {
            
            // 3. Cấp "thẻ nhớ" (Session) cho khách hàng
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['ho_ten'];
            $_SESSION['role'] = $user['vai_tro'];
            $_SESSION['balance'] = $user['so_du']; // Lưu số dư để lát hiển thị lên Menu

            // 4. Kiểm tra vai trò để điều hướng
            if ($user['vai_tro'] === 'admin') {
                // Nếu là sếp tổng, mời vào trang quản trị
                header("Location: ../admin/index.php");
            } else {
                // Nếu là khách hàng, mời ra trang chủ lựa vé
                header("Location: ../index.php");
            }
            exit();

        } else {
            // Đăng nhập sai (Sai tên hoặc sai pass)
            echo "<script>
                    alert('Sai tên đăng nhập hoặc mật khẩu!');
                    window.history.back();
                  </script>";
        }

    } catch (PDOException $e) {
        echo "Lỗi hệ thống: " . $e->getMessage();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>