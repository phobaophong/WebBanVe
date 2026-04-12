<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$action = isset($_REQUEST['action_type']) ? $_REQUEST['action_type'] : '';

// ==========================================
// THÊM VÉ BÁN (TỪ FORM)
// ==========================================
if ($action === 'add' && $_SERVER["REQUEST_METHOD"] == "POST") {
    $id_trandau = (int)$_POST['id_trandau'];
    $id_hangve = (int)$_POST['id_hangve'];
    $gia_tien = (float)$_POST['gia_tien'];
    $so_luong = (int)$_POST['so_luong'];

    if ($id_trandau <= 0 || $id_hangve <= 0 || $gia_tien < 0 || $so_luong <= 0) {
        $_SESSION['error'] = "Dữ liệu nhập vào không hợp lệ!";
        header("Location: ../admin/manage_tickets.php?id_trandau=" . $id_trandau);
        exit();
    }

    try {
        // Kiểm tra xem hạng vé này đã được tạo cho trận này chưa
        $check = $conn->prepare("SELECT id FROM tbl_ve WHERE id_trandau = ? AND id_hangve = ?");
        $check->execute([$id_trandau, $id_hangve]);
        
        if ($check->rowCount() > 0) {
            $_SESSION['error'] = "Hạng vé này đã được mở bán cho trận đấu này rồi!";
        } else {
            // Thêm vé mới
            $stmt = $conn->prepare("INSERT INTO tbl_ve (id_trandau, id_hangve, gia_tien, so_luong_con) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_trandau, $id_hangve, $gia_tien, $so_luong]);
            $_SESSION['success_msg'] = "Mở bán vé thành công!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi Database: " . $e->getMessage();
    }
    
    header("Location: ../admin/manage_tickets.php?id_trandau=" . $id_trandau);
    exit();
}

// ==========================================
// XÓA VÉ ĐANG BÁN (TỪ NÚT XÓA)
// ==========================================
elseif ($action === 'delete' && isset($_GET['id_ve'])) {
    $id_ve = (int)$_GET['id_ve'];
    $id_trandau = (int)$_GET['id_trandau'];

    try {
        $stmt = $conn->prepare("DELETE FROM tbl_ve WHERE id = ?");
        $stmt->execute([$id_ve]);
        $_SESSION['success_msg'] = "Đã thu hồi vé thành công!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Không thể xóa vé vì đã có khách hàng mua (dính khóa ngoại Đơn hàng).";
    }

    header("Location: ../admin/manage_tickets.php?id_trandau=" . $id_trandau);
    exit();
} 

else {
    header("Location: ../admin/index.php");
    exit();
}
?>