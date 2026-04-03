<?php
// Kiểm tra xem session đã được khởi tạo chưa (để tránh lỗi nếu có trang gọi 2 lần)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-warning" href="/WebBanVe/index.php">🏆 TICKET WC26</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/WebBanVe/index.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="/WebBanVe/pages/match_list.php">Lịch thi đấu & Mua vé</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Hướng dẫn</a></li>
            </ul>
            
            <div class="d-flex align-items-center">
                <?php 
                // KIỂM TRA SESSION: Nếu đã đăng nhập (có tồn tại $_SESSION['user_id'])
                if (isset($_SESSION['user_id'])): 
                ?>
                    <span class="text-warning fw-bold me-3">
                        💰 Số dư: <?php echo number_format($_SESSION['balance'], 0, ',', '.'); ?> VNĐ
                    </span>
                    
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Lịch sử mua vé</a></li>
                            <li><a class="dropdown-item" href="#">Nạp tiền</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/WebBanVe/actions/process_logout.php">Đăng xuất</a></li>
                        </ul>
                    </div>

                <?php 
                // NẾU CHƯA ĐĂNG NHẬP: Hiện 2 nút mặc định
                else: 
                ?>
                    <a href="/WebBanVe/pages/login.php" class="btn btn-outline-light me-2">Đăng nhập</a>
                    <a href="/WebBanVe/pages/register.php" class="btn btn-warning fw-bold">Đăng ký</a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</nav>