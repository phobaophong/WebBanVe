<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/database.php'; 
include '../includes/header.php';
include '../includes/navbar.php';

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
?>

<div class="container">
    <div class="auth-container">
        <h2 class="auth-title">🔒 ĐĂNG NHẬP</h2>
        
        <?php 
        if (isset($_SESSION['error'])) {
            echo "<div class='alert-error'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']); 
        }
        if (isset($_SESSION['success_msg'])) {
            echo "<div class='alert alert-success alert-custom-success'>" . $_SESSION['success_msg'] . "</div>";
            unset($_SESSION['success_msg']);
        }
        ?>

        <form action="../actions/process_login.php" method="POST">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">

            <div class="form-group">
                <label class="font-weight-bold">Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" required placeholder="Nhập tài khoản của bạn">
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required placeholder="Nhập mật khẩu">
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-auth">ĐĂNG NHẬP VÀO HỆ THỐNG</button>
        </form>

        <div class="auth-switch">
            <span>Chưa có tài khoản?</span> 
            <a href="register.php<?php echo !empty($redirect) ? '?redirect='.urlencode($redirect) : ''; ?>">Đăng ký ngay</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>