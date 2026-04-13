<nav class="header">
    <div class="custom-navbar">

        <div class="nav-left">
            <a href="<?php echo $base_url; ?>/index.php" class="logo">
                <img src="<?php echo $base_url; ?>/assets/images/system/logo_web.png" class="sys-icon" alt="icon"> Vé Bóng Đá Online
            </a>
        </div>

        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>

                <div class="user-box">
                    <span class="user-name">
                        <img src="<?php echo $base_url; ?>/assets/images/system/icon-user.png" class="sys-icon" alt="icon"> 
                        <?php echo htmlspecialchars(isset($_SESSION['ho_ten']) && !empty($_SESSION['ho_ten']) ? $_SESSION['ho_ten'] : $_SESSION['username']); ?>
                    </span>

                    <div class="dropdown-menu-custom">

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'khach_hang'): ?>
                            <div class="dropdown-item">
                                <img src="<?php echo $base_url; ?>/assets/images/system/icon-money.png" class="sys-icon" alt="icon"> 
                                Số dư: <strong class="balance-amount"><?php echo number_format(isset($_SESSION['so_du']) ? $_SESSION['so_du'] : 0, 0, ',', '.'); ?>đ</strong>
                            </div>

                            <a href="<?php echo $base_url; ?>/pages/deposit.php" class="dropdown-item deposit-link">
                                <img src="<?php echo $base_url; ?>/assets/images/system/icon-card.png" class="sys-icon" alt="icon"> Nạp tiền
                            </a>

                            <a href="<?php echo $base_url; ?>/pages/history.php" class="dropdown-item">
                                <img src="<?php echo $base_url; ?>/assets/images/system/icon-history.png" class="sys-icon" alt="icon"> Vé của tôi
                            </a>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="<?php echo $base_url; ?>/admin/index.php" class="dropdown-item">
                                <img src="<?php echo $base_url; ?>/assets/images/system/icon-settings.png" class="sys-icon" alt="icon"> Quản trị
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo $base_url; ?>/actions/process_logout.php" class="dropdown-item logout">
                            <img src="<?php echo $base_url; ?>/assets/images/system/icon-logout.png" class="sys-icon" alt="icon"> Đăng xuất
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <a href="<?php echo $base_url; ?>/pages/login.php" class="btn-login">Đăng nhập</a>
                <a href="<?php echo $base_url; ?>/pages/register.php" class="btn-register">Đăng ký</a>
            <?php endif; ?>
        </div>

    </div>

    <div class="warning-ticker">
        <span> CẢNH BÁO: Tránh xa các hành vi cá độ bóng đá dưới mọi hình thức. Cá độ bóng đá là hành vi vi phạm pháp luật và bị Nhà nước Việt Nam nghiêm cấm! </span>
    </div>

</nav>