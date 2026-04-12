<?php
session_start();
require_once '../config/database.php';

// Kiểm tra đăng nhập (Bảo mật 2 lớp)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_nguoidung = $_SESSION['user_id'];
    $id_trandau = (int)$_POST['id_trandau'];
    $id_ve = (int)$_POST['id_ve'];
    $so_luong_mua = (int)$_POST['so_luong'];

    if ($id_ve <= 0 || $so_luong_mua <= 0) {
        $_SESSION['error'] = "Dữ liệu vé không hợp lệ!";
        header("Location: ../pages/checkout.php?id_trandau=" . $id_trandau);
        exit();
    }

    try {
        // 1. BẮT ĐẦU TRANSACTION (Bảo vệ tính toàn vẹn dữ liệu)
        $conn->beginTransaction();

        // 2. Lấy thông tin vé hiện tại & Dùng FOR UPDATE để khóa dòng này lại chống mua trùng
        $stmt_ve = $conn->prepare("
            SELECT v.so_luong_con, v.gia_tien, h.ten_hang, 
                   dn.ten_doi AS ten_nha, dk.ten_doi AS ten_khach
            FROM tbl_ve v
            JOIN tbl_hangve h ON v.id_hangve = h.id
            JOIN tbl_trandau t ON v.id_trandau = t.id
            JOIN tbl_doibong dn ON t.id_doi_nha = dn.id
            JOIN tbl_doibong dk ON t.id_doi_khach = dk.id
            WHERE v.id = :id_ve FOR UPDATE
        ");
        $stmt_ve->execute(['id_ve' => $id_ve]);
        $ve_info = $stmt_ve->fetch(PDO::FETCH_ASSOC);

        if (!$ve_info) {
            throw new Exception("Hệ thống không tìm thấy thông tin hạng vé này!");
        }

        // 3. Kiểm tra số lượng vé thực tế còn lại trong kho
        if ($ve_info['so_luong_con'] < $so_luong_mua) {
            throw new Exception("Rất tiếc! Số vé bạn chọn đã vượt quá số lượng trong kho. Hạng vé này chỉ còn " . $ve_info['so_luong_con'] . " vé.");
        }

        // 4. Tính toán và chuẩn bị Data lưu vào tbl_donhang
        $tong_tien = $ve_info['gia_tien'] * $so_luong_mua;
        $ten_trandau_snapshot = $ve_info['ten_nha'] . " vs " . $ve_info['ten_khach'];
        $ten_hangve_snapshot = $ve_info['ten_hang'];

        // 5. TRỪ VÉ (Update tbl_ve)
        $stmt_update_ve = $conn->prepare("UPDATE tbl_ve SET so_luong_con = so_luong_con - :so_luong WHERE id = :id_ve");
        $stmt_update_ve->execute([
            'so_luong' => $so_luong_mua,
            'id_ve' => $id_ve
        ]);

        // 6. TẠO ĐƠN HÀNG (Insert tbl_donhang)
        $stmt_donhang = $conn->prepare("
            INSERT INTO tbl_donhang (id_nguoidung, id_ve, so_luong, tong_tien, ten_trandau, ten_hangve) 
            VALUES (:id_user, :id_ve, :so_luong, :tong_tien, :ten_td, :ten_hv)
        ");
        $stmt_donhang->execute([
            'id_user'   => $id_nguoidung,
            'id_ve'     => $id_ve,
            'so_luong'  => $so_luong_mua,
            'tong_tien' => $tong_tien,
            'ten_td'    => $ten_trandau_snapshot,
            'ten_hv'    => $ten_hangve_snapshot
        ]);

        // 7. HOÀN TẤT TRANSACTION
        $conn->commit();

        // Mua thành công! Báo cáo người dùng
        $_SESSION['success_msg'] = "🎉 Thanh toán thành công! Bạn đã đặt $so_luong_mua vé trận $ten_trandau_snapshot.";
        
        // Đưa về trang chủ (Sau này có trang lịch sử thì đổi ../index.php thành ../pages/history.php)
        header("Location: ../index.php");
        exit();

    } catch (Exception $e) {
        // NẾU CÓ LỖI (Ví dụ có ai đó mua hết vé trước) -> HỦY LỆNH, TRẢ VỀ BAN ĐẦU
        $conn->rollBack();
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../pages/checkout.php?id_trandau=" . $id_trandau);
        exit();
    }
} else {
    // Truy cập không hợp lệ
    header("Location: ../index.php");
    exit();
}
?>