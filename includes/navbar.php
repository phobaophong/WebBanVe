<nav class="header">
    <div class="custom-navbar">

        <div class="nav-left">
            <a href="<?php echo $base_url; ?>/index.php" class="logo" style="text-decoration: none;">
                ⚽ Vé Bóng Đá
            </a>
        </div>

        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>

                <div class="user-box">
                    <span class="user-name">
                        👋 <?php echo htmlspecialchars(isset($_SESSION['ho_ten']) && !empty($_SESSION['ho_ten']) ? $_SESSION['ho_ten'] : $_SESSION['username']); ?>
                    </span>

                    <div class="dropdown-menu-custom">

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'khach_hang'): ?>
                            <div class="dropdown-item">
                                💰 Số dư: <strong style="color: #c42127;"><?php echo number_format(isset($_SESSION['so_du']) ? $_SESSION['so_du'] : 0, 0, ',', '.'); ?>đ</strong>
                            </div>

                            <a href="<?php echo $base_url; ?>/pages/deposit.php" class="dropdown-item" style="font-weight: 500; color: #28a745;">
                                💳 Nạp tiền
                            </a>

                            <a href="<?php echo $base_url; ?>/pages/history.php" class="dropdown-item">
                                📜 Vé của tôi
                            </a>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="<?php echo $base_url; ?>/admin/index.php" class="dropdown-item">
                                ⚙️ Quản trị
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo $base_url; ?>/actions/process_logout.php" class="dropdown-item logout">
                            🚪 Đăng xuất
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <a href="<?php echo $base_url; ?>/pages/login.php" style="color: white; text-decoration: none; font-weight: 500;">Đăng nhập</a>
                <a href="<?php echo $base_url; ?>/pages/register.php" class="btn-register">Đăng ký</a>
            <?php endif; ?>
        </div>

    </div>

    <div class="warning-ticker">
        <span>⚠️ CẢNH BÁO: Tránh xa các hành vi cá độ bóng đá dưới mọi hình thức. Cá độ bóng đá là hành vi vi phạm pháp luật và bị Nhà nước Việt Nam nghiêm cấm! ⚠️</span>
    </div>

</nav>