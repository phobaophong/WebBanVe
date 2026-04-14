<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$base_url = "/WEBBANVE";

$thong_bao = isset($_SESSION['scan_msg']) ? $_SESSION['scan_msg'] : "";
$loai_thong_bao = isset($_SESSION['scan_type']) ? $_SESSION['scan_type'] : "";
$chi_tiet_ve = isset($_SESSION['scan_ticket_info']) ? $_SESSION['scan_ticket_info'] : null;

unset($_SESSION['scan_msg'], $_SESSION['scan_type'], $_SESSION['scan_ticket_info']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống Soát Vé - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="admin-body">

    <header class="admin-header">
        <h1 class="admin-title">
            <img src="<?php echo $base_url; ?>/assets/images/system/icon-qr.png" class="sys-icon" alt="icon"> TRẠM KIỂM SOÁT VÉ QR
        </h1>
        <div class="admin-nav-links">
            <a href="index.php">
                <img src="<?php echo $base_url; ?>/assets/images/system/icon-back.png" class="sys-icon" alt="icon"> Trở về Trang quản trị
            </a>
        </div>
    </header>

    <div class="container scan-container">
        <div class="admin-table-container text-center">
            
            <div id="reader" class="qr-reader-box"></div>

            <h5 class="mb-3 text-secondary font-weight-bold">HOẶC NHẬP MÃ THỦ CÔNG</h5>
            
            <form action="../actions/process_scan.php" method="POST" id="scanForm">
                <div class="form-group">
                    <input type="text" name="ma_ve" id="ma_ve_input" class="form-control form-control-lg text-center scan-input font-weight-bold" 
                           placeholder="Mã sẽ tự hiện khi quét thành công..." autofocus required autocomplete="off">
                </div>
                <button type="submit" class="btn btn-success btn-lg btn-block mt-3 btn-rounded">
                    <img src="<?php echo $base_url; ?>/assets/images/system/icon-search.png" class="sys-icon-btn" alt="icon"> KIỂM TRA MÃ
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
                        <img src="<?php echo $base_url; ?>/assets/images/system/icon-ball.png" class="sys-icon" alt="icon"> <?php echo htmlspecialchars($chi_tiet_ve['ten_trandau']); ?>
                    </h5>
                    <p class="mb-2 ticket-text">
                        <b><img src="<?php echo $base_url; ?>/assets/images/system/icon-user.png" class="sys-icon" alt="icon"> Khách hàng:</b> <?php echo htmlspecialchars($chi_tiet_ve['ho_ten']); ?>
                    </p>
                    <p class="mb-2 ticket-text">
                        <b><img src="<?php echo $base_url; ?>/assets/images/system/icon-ticket.png" class="sys-icon" alt="icon"> Hạng vé:</b> <?php echo htmlspecialchars($chi_tiet_ve['ten_hangve']); ?>
                    </p>
                    <p class="mb-3 ticket-text">
                        <b><img src="<?php echo $base_url; ?>/assets/images/system/icon-tag.png" class="sys-icon" alt="icon"> Mã code:</b> <span class="text-primary font-weight-bold"><?php echo htmlspecialchars($chi_tiet_ve['ma_donhang_qr']); ?></span>
                    </p>
                    
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

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            html5QrcodeScanner.clear();
            document.getElementById('ma_ve_input').value = decodedText;
            document.getElementById('scanForm').submit();
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { fps: 10, qrbox: {width: 250, height: 250} }, 
            false
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>