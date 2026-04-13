<?php
session_start();
require_once '../config/database.php';

// Bảo vệ quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ma_ve'])) {
    $ma_ve = trim($_POST['ma_ve']);

    if (empty($ma_ve)) {
        $_SESSION['scan_msg'] = "Vui lòng quét mã QR hoặc nhập mã vé!";
        $_SESSION['scan_type'] = "danger";
    } else {
        $sql = "SELECT c.*, d.ten_trandau, d.ten_hangve, u.ho_ten 
                FROM tbl_chitiet_donhang c
                JOIN tbl_donhang d ON c.id_donhang = d.id
                JOIN tbl_nguoidung u ON d.id_nguoidung = u.id
                WHERE c.ma_donhang_qr = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ma_ve]);
        $chi_tiet_ve = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$chi_tiet_ve) {
            $_SESSION['scan_msg'] = "❌ VÉ GIẢ! Không tìm thấy mã vé này trong hệ thống.";
            $_SESSION['scan_type'] = "danger";
        } else {
            if ($chi_tiet_ve['trang_thai_donhang'] === 'da_su_dung') {
                $_SESSION['scan_msg'] = "⚠️ CẢNH BÁO: Vé này ĐÃ ĐƯỢC QUÉT TRƯỚC ĐÓ. Có dấu hiệu gian lận!";
                $_SESSION['scan_type'] = "warning";
                $_SESSION['scan_ticket_info'] = $chi_tiet_ve; // Gửi thông tin vé qua Session
            } else {
                // Đổi trạng thái vé
                $stmt_update = $conn->prepare("UPDATE tbl_chitiet_donhang SET trang_thai_donhang = 'da_su_dung' WHERE ma_donhang_qr = ?");
                $stmt_update->execute([$ma_ve]);
                
                $_SESSION['scan_msg'] = "✅ SOÁT VÉ THÀNH CÔNG! Cho phép khách qua cửa.";
                $_SESSION['scan_type'] = "success";
                
                // Cập nhật lại trạng thái ảo để hiển thị
                $chi_tiet_ve['trang_thai_donhang'] = 'da_su_dung'; 
                $_SESSION['scan_ticket_info'] = $chi_tiet_ve; // Gửi thông tin vé qua Session
            }
        }
    }
    // Trả về lại trang giao diện quét vé
    header("Location: ../admin/scan_ticket.php");
    exit();
} else {
    header("Location: ../admin/scan_ticket.php");
    exit();
}
?>