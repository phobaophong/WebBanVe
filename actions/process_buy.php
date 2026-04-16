<?php
session_start();
require_once '../config/database.php';

// Kiểm tra đăng nhập
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
        $conn->beginTransaction();

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

        if ($ve_info['so_luong_con'] < $so_luong_mua) {
            throw new Exception("Rất tiếc! Hạng vé này chỉ còn " . $ve_info['so_luong_con'] . " vé.");
        }

        $tong_tien = $ve_info['gia_tien'] * $so_luong_mua;

        $stmt_user = $conn->prepare("SELECT so_du FROM tbl_nguoidung WHERE id = :id_user FOR UPDATE");
        $stmt_user->execute(['id_user' => $id_nguoidung]);
        $user_info = $stmt_user->fetch(PDO::FETCH_ASSOC);

        if ($user_info['so_du'] < $tong_tien) {
            throw new Exception("Tài khoản của bạn không đủ số dư! (Cần " . number_format($tong_tien, 0, ',', '.') . " VNĐ). Vui lòng nạp thêm tiền.");
        }

        $stmt_tru_tien = $conn->prepare("UPDATE tbl_nguoidung SET so_du = so_du - :tong_tien WHERE id = :id_user");
        $stmt_tru_tien->execute(['tong_tien' => $tong_tien, 'id_user' => $id_nguoidung]);

        $stmt_thanhtoan = $conn->prepare("INSERT INTO tbl_thanhtoan (id_nguoidung, so_tien, trang_thai) VALUES (:id_user, :so_tien, 'thanh_cong')");
        $stmt_thanhtoan->execute(['id_user' => $id_nguoidung, 'so_tien' => $tong_tien]);

        $stmt_update_ve = $conn->prepare("UPDATE tbl_ve SET so_luong_con = so_luong_con - :so_luong WHERE id = :id_ve");
        $stmt_update_ve->execute(['so_luong' => $so_luong_mua, 'id_ve' => $id_ve]);

        $ten_trandau_snapshot = $ve_info['ten_nha'] . " vs " . $ve_info['ten_khach'];
        $ten_hangve_snapshot = $ve_info['ten_hang'];

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


        $id_donhang_vua_tao = $conn->lastInsertId(); 
        
        $stmt_chitiet = $conn->prepare("INSERT INTO tbl_chitiet_donhang (id_donhang, ma_donhang_qr) VALUES (?, ?)");

        for ($i = 0; $i < $so_luong_mua; $i++) {
            // Đẻ ra mã kiểu VBD-A8B9C-1, VBD-A8B9C-2...
            $ma_qr_le = "VBD-" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6)) . "-" . ($i + 1);
            $stmt_chitiet->execute([$id_donhang_vua_tao, $ma_qr_le]);
        }

        $conn->commit();

        $_SESSION['so_du'] -= $tong_tien;

        $_SESSION['success_msg'] = "Thanh toán thành công! Bạn đã đặt $so_luong_mua vé trận $ten_trandau_snapshot.";
        header("Location: ../pages/history.php");
        exit();

    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../pages/checkout.php?id_trandau=" . $id_trandau);
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>