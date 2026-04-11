<?php
// Bắt buộc phải có session_start() ở dòng đầu tiên
session_start();

// ==========================================================================
// 1. TRẠM KIỂM SOÁT ĐĂNG NHẬP
// ==========================================================================
if (!isset($_SESSION['user_id'])) {
    // Lấy ID trận đấu mà khách đang muốn mua
    $id_trandau = isset($_GET['id_trandau']) ? $_GET['id_trandau'] : '';
    
    // Lưu một thông báo lỗi vào session để hiện ở trang đăng nhập
    $_SESSION['error'] = "Bạn cần đăng nhập để tiến hành mua vé!";
    
    // Chuyển hướng sang trang đăng nhập (Kèm theo biến redirect để xíu nữa quay lại)
    if ($id_trandau != '') {
        header("Location: login.php?redirect=checkout.php?id_trandau=" . $id_trandau);
    } else {
        header("Location: login.php");
    }
    exit(); // Bắt buộc phải có exit() để dừng ngay việc tải giao diện bên dưới
}

// ==========================================================================
// 2. NẾU ĐÃ ĐĂNG NHẬP THÌ CHẠY TIẾP CODE HIỂN THỊ GIAO DIỆN MUA VÉ
// ==========================================================================
require_once '../config/database.php'; 
include '../includes/header.php';
include '../includes/navbar.php';

// Code lấy thông tin trận đấu từ database và hiển thị form thanh toán sẽ nằm ở đây...
// ...
?>

<div class="container mt-5">
    <div class="alert alert-success">
        <h4>🎉 Chúc mừng <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
        <p>Bạn đã đăng nhập thành công và có quyền mua vé cho trận đấu này.</p>
    </div>
    
    </div>

<?php include '../includes/footer.php'; ?>