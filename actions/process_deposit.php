<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'khach_hang') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_nguoidung = $_SESSION['user_id'];
    $so_tien_nap = (float)$_POST['so_tien'];

    if ($so_tien_nap < 10000) {
        $_SESSION['error'] = "Số tiền nạp tối thiểu là 10.000đ!";
        header("Location: ../pages/deposit.php");
        exit();
    }

    try {
        $conn->beginTransaction();

        // 1. Cập nhật số dư trong bảng tbl_nguoidung
        $stmt_update = $conn->prepare("UPDATE tbl_nguoidung SET so_du = so_du + :tien WHERE id = :id_user");
        $stmt_update->execute(['tien' => $so_tien_nap, 'id_user' => $id_nguoidung]);

        // 2. Lưu lại lịch sử giao dịch nạp tiền vào tbl_thanhtoan
        $stmt_log = $conn->prepare("INSERT INTO tbl_thanhtoan (id_nguoidung, so_tien, trang_thai) VALUES (:id_user, :tien, 'thanh_cong')");
        $stmt_log->execute(['id_user' => $id_nguoidung, 'tien' => $so_tien_nap]);

        $conn->commit();

        // 3. Cập nhật lại Session để hiển thị ngay trên Navbar
        $_SESSION['so_du'] += $so_tien_nap;

        $_SESSION['success_msg'] = "🎉 Nạp thành công " . number_format($so_tien_nap, 0, ',', '.') . "đ vào tài khoản!";
        header("Location: ../pages/deposit.php");
        exit();

    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
        header("Location: ../pages/deposit.php");
        exit();
    }
} else {
    header("Location: ../pages/deposit.php");
    exit();
}
?>