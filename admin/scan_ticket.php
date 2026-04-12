<?php
session_start();
require_once '../config/database.php';

// Chỉ cho phép Admin (Bảo vệ soát vé) vào trang này
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$base_url = "/WEBBANVE";
$thong_bao = "";
$loai_thong_bao = "";
$chi_tiet_ve = null;

// XỬ LÝ KHI CÓ MÃ VÉ ĐƯỢC GỬI LÊN (Do máy quét QR tự động submit)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ma_ve'])) {
    $ma_ve = trim($_POST['ma_ve']);

    if (empty($ma_ve)) {
        $thong_bao = "Vui lòng quét mã QR hoặc nhập mã vé!";
        $loai_thong_bao = "danger";
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
            $thong_bao = "❌ VÉ GIẢ! Không tìm thấy mã vé này trong hệ thống.";
            $loai_thong_bao = "danger";
        } else {
            if ($chi_tiet_ve['trang_thai_donhang'] === 'da_su_dung') {
                $thong_bao = "⚠️ CẢNH BÁO: Vé này ĐÃ ĐƯỢC QUÉT TRƯỚC ĐÓ. Có dấu hiệu gian lận!";
                $loai_thong_bao = "warning";
            } else {
                $stmt_update = $conn->prepare("UPDATE tbl_chitiet_donhang SET trang_thai_donhang = 'da_su_dung' WHERE ma_donhang_qr = ?");
                $stmt_update->execute([$ma_ve]);
                
                $thong_bao = "✅ SOÁT VÉ THÀNH CÔNG! Cho phép khách qua cửa.";
                $loai_thong_bao = "success";
                
                $chi_tiet_ve['trang_thai_donhang'] = 'da_su_dung'; 
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống Soát Vé - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">

    <header class="admin-header">
        <h1 class="admin-title">📷 TRẠM KIỂM SOÁT VÉ QR</h1>
        <div class="admin-nav-links">
            <a href="index.php">🔙 Trở về Trang quản trị</a>
        </div>
    </header>

    <div class="container scan-container">
        <div class="admin-table-container text-center">
            <h3 class="mb-4 text-primary font-weight-bold">HƯỚNG MÁY QUÉT VÀO ĐÂY</h3>
            
            <form action="scan_ticket.php" method="POST">
                <div class="form-group">
                    <input type="text" name="ma_ve" class="form-control form-control-lg text-center scan-input font-weight-bold" 
                           placeholder="Quét mã QR..." autofocus required autocomplete="off">
                </div>
                <button type="submit" class="btn btn-success btn-lg btn-block mt-3 btn-rounded">
                    🔍 KIỂM TRA MÃ THỦ CÔNG
                </button>
            </form>

            <?php if (!empty($thong_bao)): ?>
                <div class="alert alert-<?php echo $loai_thong_bao; ?> mt-4 font-weight-bold alert-scan">
                    <?php echo $thong_bao; ?>
                </div>
            <?php endif; ?>

            <?php if ($chi_tiet_ve): ?>
                <div class="ticket-info text-left">
                    <h5 class="text-danger font-weight-bold border-bottom pb-2 mb-3">
                        ⚽ <?php echo htmlspecialchars($chi_tiet_ve['ten_trandau']); ?>
                    </h5>
                    <p class="mb-2 ticket-text"><b>👤 Khách hàng:</b> <?php echo htmlspecialchars($chi_tiet_ve['ho_ten']); ?></p>
                    <p class="mb-2 ticket-text"><b>🎟️ Hạng vé:</b> <?php echo htmlspecialchars($chi_tiet_ve['ten_hangve']); ?></p>
                    <p class="mb-3 ticket-text"><b>🏷️ Mã code:</b> <span class="text-primary font-weight-bold"><?php echo htmlspecialchars($chi_tiet_ve['ma_donhang_qr']); ?></span></p>
                    
                    <div class="text-center mt-4">
                        <?php if ($chi_tiet_ve['trang_thai_donhang'] === 'da_su_dung'): ?>
                            <span class="badge badge-secondary badge-scan">Trạng thái: Đã thu hồi</span>
                        <?php else: ?>
                            <span class="badge badge-warning badge-scan">Trạng thái: Chưa sử dụng</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>