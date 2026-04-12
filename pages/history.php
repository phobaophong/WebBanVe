<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'khach_hang') {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['user_id'];
$base_url = "/WEBBANVE";

$sql = "SELECT d.*, 
        (SELECT GROUP_CONCAT(CONCAT(ma_donhang_qr, ':', trang_thai_donhang) SEPARATOR '|') 
         FROM tbl_chitiet_donhang WHERE id_donhang = d.id) as danh_sach_ve
        FROM tbl_donhang d 
        WHERE d.id_nguoidung = ? 
        ORDER BY d.ngay_dat DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_user]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container">
    <div class="user-page-container history-wrapper">
        <h2 class="text-center text-primary font-weight-bold mb-4">📜 VÉ ĐÃ MUA CỦA BẠN</h2>

        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class='alert alert-success alert-custom-success'><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
        <?php endif; ?>

        <?php if (count($orders) > 0): ?>
            <?php foreach($orders as $o): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold">Đơn hàng #<?php echo $o['id']; ?> - <?php echo date('d/m/Y H:i', strtotime($o['ngay_dat'])); ?></span>
                        <span class="badge badge-success badge-price"><?php echo number_format($o['tong_tien'], 0, ',', '.'); ?>đ</span>
                    </div>
                    <div class="card-body">
                        <h5 class="text-danger font-weight-bold"><?php echo htmlspecialchars($o['ten_trandau']); ?></h5>
                        <p class="mb-2">Hạng vé: <b><?php echo htmlspecialchars($o['ten_hangve']); ?></b> | Số lượng: <b><?php echo $o['so_luong']; ?> vé</b></p>
                        
                        <div class="row mt-4">
                            <?php 
                            if (!empty($o['danh_sach_ve'])) {
                                $ve_array = explode('|', $o['danh_sach_ve']);
                                foreach($ve_array as $ve_info) {
                                    list($ma, $status) = explode(':', $ve_info);
                                    ?>
                                    <div class="col-6 col-md-3 text-center mb-3">
                                        <div class="p-2 border rounded bg-white shadow-sm">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?php echo $ma; ?>" alt="QR" class="img-fluid mb-2">
                                            
                                            <div class="mb-1 qr-code-text"><?php echo $ma; ?></div>
                                            
                                            <?php if($status == 'da_su_dung'): ?>
                                                <span class="badge badge-secondary badge-status-sm">Đã soát vé</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning badge-status-sm">Chưa sử dụng</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<div class='col-12 text-muted text-center'><em>(Đơn hàng cũ không có mã QR)</em></div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-history text-center p-5">
                <h4>Bạn chưa có vé nào!</h4>
                <a href="../index.php" class="btn btn-primary font-weight-bold mt-3">MUA VÉ NGAY</a>
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-4">
            <a href="../index.php" class="btn-back-home">🔙 Quay lại Trang chủ</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>