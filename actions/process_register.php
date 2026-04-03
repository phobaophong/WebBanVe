<?php
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = trim($_POST['txt_username']);
    $password = $_POST['txt_password'];
    $hoten = trim($_POST['txt_hoten']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // BẮT ĐẦU GIAO DỊCH (Transaction)
        $conn->beginTransaction();

        // 1. Tạo tài khoản (Số dư mặc định CSDL tự set là 0)
        $sql_insert = "INSERT INTO tbl_nguoidung (ten_dang_nhap, mat_khau, ho_ten) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->execute([$username, $hashed_password, $hoten]);

        // 2. Lấy ID của tài khoản vừa tạo thành công
        $new_user_id = $conn->lastInsertId();

        $tien_thuong = 100000000;
        $sql_bonus = "UPDATE tbl_nguoidung SET so_du = so_du + ? WHERE id = ?";
        $stmt_bonus = $conn->prepare($sql_bonus);
        $stmt_bonus->execute([$tien_thuong, $new_user_id]);

        // 4. MỚI: Ghi vào sổ lịch sử thanh toán để Admin quản lý
        $sql_history = "INSERT INTO tbl_thanhtoan (id_nguoidung, so_tien, phuong_thuc, trang_thai) VALUES (?, ?, 'khuyen_mai_dang_ky', 'thanh_cong')";
        $stmt_history = $conn->prepare($sql_history);
        $stmt_history->execute([$new_user_id, $tien_thuong]);

        // KẾT THÚC VÀ LƯU GIAO DỊCH
        $conn->commit();

        echo "<script>
                alert('Đăng ký tài khoản thành công! Vui lòng đăng nhập.');
                window.location.href = '../pages/login.php';
              </script>";

    } catch (PDOException $e) {
        // NẾU CÓ LỖI -> HỦY TOÀN BỘ (Tiền không bị cộng oan, lịch sử không bị ghi sai)
        $conn->rollBack();
        
        if ($e->getCode() == 23000) {
            echo "<script>
                    alert('Lỗi: Tên đăng nhập này đã có người sử dụng!');
                    window.history.back();
                  </script>";
        } else {
            echo "Lỗi hệ thống: " . $e->getMessage();
        }
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>