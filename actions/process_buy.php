<?php
session_start();

// 1. Kiểm tra đăng nhập (Bức tường lửa)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

require_once '../config/database.php';

// 2. Kiểm tra xem dữ liệu có được gửi từ form Mua Vé không
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_buy_ticket'])) {
    
    // Nhận dữ liệu từ form
    $id_nguoidung = $_SESSION['user_id'];
    $id_ve = $_POST['id_ve'] ?? '';
    $so_luong_mua = (int)$_POST['so_luong'];

    // Nếu cố tình không chọn loại vé mà ấn mua
    if (empty($id_ve)) {
        die("<h2 style='color:red; text-align:center;'>Lỗi: Vui lòng chọn hạng vé! <a href='javascript:history.back()'>Quay lại</a></h2>");
    }

    try {
        // =======================================================
        // BẮT ĐẦU TRANSACTION: Bảo vệ an toàn tuyệt đối cho dòng tiền
        // =======================================================
        $conn->beginTransaction();

        // Bước 1: Lấy thông tin Vé + Trận Đấu + Hạng Vé (Dùng JOIN để lấy Snapshot Data của bro)
        $sql_ve = "SELECT v.*, t.doi_nha, t.doi_khach, h.ten_hang 
                   FROM tbl_ve v 
                   JOIN tbl_trandau t ON v.id_trandau = t.id
                   JOIN tbl_hangve h ON v.id_hangve = h.id
                   WHERE v.id = ?";
        $stmt_ve = $conn->prepare($sql_ve);
        $stmt_ve->execute([$id_ve]);
        $ve = $stmt_ve->fetch(PDO::FETCH_ASSOC);

        if (!$ve || $ve['so_luong_con'] < $so_luong_mua) {
            throw new Exception("Hết vé hoặc số lượng vé không đủ đáp ứng!");
        }

        // Bước 2: Lấy thông tin ví tiền của Khách hàng
        $sql_user = "SELECT so_du FROM tbl_nguoidung WHERE id = ?";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->execute([$id_nguoidung]);
        $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

        $tong_tien = $ve['gia_tien'] * $so_luong_mua;

        if ($user['so_du'] < $tong_tien) {
            throw new Exception("Số dư không đủ! Vui lòng nạp thêm tiền vào tài khoản.");
        }

        // =======================================================
        // THỰC THI TRỪ TIỀN VÀ XUẤT VÉ
        // =======================================================
        
        // 1. Trừ tiền trong ví khách hàng
        $sql_tru_tien = "UPDATE tbl_nguoidung SET so_du = so_du - ? WHERE id = ?";
        $conn->prepare($sql_tru_tien)->execute([$tong_tien, $id_nguoidung]);

        // 2. Trừ vé trong kho
        $so_luong_con_moi = $ve['so_luong_con'] - $so_luong_mua;
        $trang_thai_ve = ($so_luong_con_moi == 0) ? 'het_ve' : 'con_ve';
        
        $sql_tru_ve = "UPDATE tbl_ve SET so_luong_con = ?, trang_thai = ? WHERE id = ?";
        $conn->prepare($sql_tru_ve)->execute([$so_luong_con_moi, $trang_thai_ve, $id_ve]);

        // 3. Cập nhật lại số dư trên màn hình Web ngay lập tức
        $_SESSION['balance'] = $user['so_du'] - $tong_tien;

        // =======================================================
        // LƯU BIÊN LAI (Sử dụng cấu trúc Snapshot siêu việt của bro)
        // =======================================================
        $ten_trandau_snapshot = $ve['doi_nha'] . " vs " . $ve['doi_khach'];
        
        $sql_donhang = "INSERT INTO tbl_donhang (id_nguoidung, id_ve, so_luong_mua, tong_tien, ten_trandau, ten_hangve, gia_ve) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $conn->prepare($sql_donhang)->execute([
            $id_nguoidung, 
            $id_ve, 
            $so_luong_mua, 
            $tong_tien, 
            $ten_trandau_snapshot, 
            $ve['ten_hang'], 
            $ve['gia_tien']
        ]);

        // MỌI THỨ HOÀN HẢO -> CHỐT GIAO DỊCH!
        $conn->commit();
        $thong_bao = "Thành công";

    } catch (Exception $e) {
        // CÓ BIẾN! -> HỦY BỎ TẤT CẢ, TIỀN TRỞ VỀ VÍ (Rollback)
        $conn->rollBack();
        $thong_bao = $e->getMessage();
    }
} else {
    // Không bấm nút mà gõ URL lụi thì đuổi về
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả giao dịch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg border-0" style="width: 450px; border-radius: 15px;">
        <div class="card-body text-center p-5">
            <?php if ($thong_bao === "Thành công"): ?>
                <h1 class="display-1 text-success mb-3">✅</h1>
                <h3 class="fw-bold text-success mb-3">THANH TOÁN THÀNH CÔNG!</h3>
                <p class="text-muted">Tuyệt vời! Bạn đã sở hữu thành công <b><?= $so_luong_mua ?></b> vé <b><?= $ve['ten_hang'] ?></b>.</p>
                <p>Trận: <b><?= $ten_trandau_snapshot ?></b></p>
                <hr>
                <p class="mb-4">Số dư còn lại: <b class="text-danger"><?= number_format($_SESSION['balance'], 0, ',', '.') ?> VNĐ</b></p>
                
                <a href="../pages/match_list.php" class="btn btn-outline-danger w-100 fw-bold rounded-pill mb-2">⬅ Tiếp tục mua vé</a>
                <a href="../index.php" class="btn btn-primary w-100 fw-bold rounded-pill">Về Trang Chủ</a>
            
            <?php else: ?>
                <h1 class="display-1 text-danger mb-3">❌</h1>
                <h3 class="fw-bold text-danger mb-3">GIAO DỊCH THẤT BẠI</h3>
                <p class="text-muted"><?= $thong_bao ?></p>
                <hr>
                <a href="javascript:history.back()" class="btn btn-secondary w-100 fw-bold rounded-pill">⬅ Quay lại trang chọn vé</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>