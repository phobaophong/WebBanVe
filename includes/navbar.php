<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, rgba(34,34,34,0.15), rgba(0,0,0,0.05));">
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
                    <li class="nav-item d-none d-lg-block"><span class="nav-link text-warning">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="btn btn-sm btn-success ml-2" href="<?php echo $base_url; ?>/admin/index.php">Admin</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>/pages/history.php">Lịch sử</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="<?php echo $base_url; ?>/actions/process_logout.php">Đăng xuất</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>/pages/login.php">Đăng Nhập</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm ml-2" href="<?php echo $base_url; ?>/pages/register.php">Đăng Ký</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="warning-ticker">
    <p>⚠️ CẢNH BÁO: Tránh xa các hành vi cá độ bóng đá dưới mọi hình thức. Cá độ bóng đá là hành vi vi phạm pháp luật và bị Nhà nước Việt Nam nghiêm cấm! ⚠️</p>
</div>