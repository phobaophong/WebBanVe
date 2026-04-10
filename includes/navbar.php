<style>
    /* CSS tinh chỉnh riêng cho Navbar */
    .custom-navbar {
        background: linear-gradient(90deg, #141E30 0%, #243B55 100%);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        padding: 12px 0;
    }
    .custom-navbar .navbar-brand span {
        font-weight: 700;
        font-size: 1.4rem;
        letter-spacing: 0.5px;
        color: #ffffff;
    }
    .custom-navbar .nav-link {
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9) !important;
        transition: color 0.3s ease;
        text-transform: uppercase;
        font-size: 0.9rem;
        margin: 0 8px;
    }
    .custom-navbar .nav-link:hover {
        color: #ffc107 !important; /* Đổi sang màu vàng khi di chuột */
    }
    .custom-btn-register {
        border-radius: 20px; /* Bo góc tròn trịa hơn */
        padding: 6px 20px;
        font-weight: 600;
        border: 2px solid #ffffff;
        transition: all 0.3s ease;
    }
    .custom-btn-register:hover {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #141E30 !important; /* Chữ tối màu khi nền vàng */
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo $base_url; ?>/index.php">
            <img src="<?php echo $base_url; ?>/assets/images/logos/logo.png" alt="logo" style="width:48px;height:48px;object-fit:contain;margin-right:10px;" onerror="this.style.display='none'">
            <span>Vé Bóng Đá Online</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ml-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>/index.php">Trang Chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>/pages/match_list.php">Lịch thi đấu</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item d-none d-lg-block"><span class="nav-link text-warning" style="text-transform: none;">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="btn btn-sm btn-success ml-2" href="<?php echo $base_url; ?>/admin/index.php" style="border-radius: 15px;">Admin</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>/pages/history.php">Lịch sử</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="<?php echo $base_url; ?>/actions/process_logout.php">Đăng xuất</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>/pages/login.php">Đăng Nhập</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm ml-2 custom-btn-register" href="<?php echo $base_url; ?>/pages/register.php">Đăng Ký</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="warning-ticker">
    <p>⚠️ CẢNH BÁO: Tránh xa các hành vi cá độ bóng đá dưới mọi hình thức. Cá độ bóng đá là hành vi vi phạm pháp luật và bị Nhà nước Việt Nam nghiêm cấm! ⚠️</p>
</div>