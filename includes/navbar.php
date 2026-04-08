<nav class="navbar">
    <div class="logo">
        <a href="<?php echo $base_url; ?>/index.php">⚽ Vé Bóng Đá Online</a>
    </div>
    
    <ul class="nav-links">
        <li><a href="<?php echo $base_url; ?>/index.php">Trang Chủ</a></li>
        <li><a href="<?php echo $base_url; ?>/pages/match_list.php">Lịch Thi Đấu</a></li>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><span class="user-greeting">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span></li>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a href="<?php echo $base_url; ?>/admin/index.php" class="btn-admin-nav">Khu Vực Quản Trị</a></li>
            <?php endif; ?>
            
            <li><a href="<?php echo $base_url; ?>/pages/history.php">Lịch sử mua vé</a></li>
            <li><a href="<?php echo $base_url; ?>/actions/process_logout.php" class="logout-link">[ Đăng xuất ]</a></li>
        <?php else: ?>
            <li><a href="<?php echo $base_url; ?>/pages/login.php">Đăng Nhập</a></li>
            <li><a href="<?php echo $base_url; ?>/pages/register.php" class="nav-btn-register">Đăng Ký</a></li>
        <?php endif; ?>
    </ul>
</nav><div class="warning-ticker">
    <p>⚠️ CẢNH BÁO: Tránh xa các hành vi cá độ bóng đá dưới mọi hình thức. Cá độ bóng đá là hành vi vi phạm pháp luật và bị Nhà nước Việt Nam nghiêm cấm! ⚠️</p>
</div>