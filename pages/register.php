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
    <div class="auth-container auth-container-lg"> 
        <h2 class="auth-title"> TẠO TÀI KHOẢN</h2>
        
        <?php 
        if (isset($_SESSION['error'])) {
            echo "<div class='alert-error'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']); 
        }
        ?>

        <form action="../actions/process_register.php" method="POST">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">

            <div class="form-group">
                <label>Họ và tên đầy đủ <span class="text-danger">*</span></label>
                <input type="text" name="fullname" class="form-control" required placeholder="VD: Nguyễn Văn A">
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" required placeholder="email@gmail.com">
                </div>
                <div class="col-md-6 form-group">
                    <label>Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" required placeholder="VD: 0987654321">
                </div>
            </div>

            <div class="form-group">
                <label>Tên đăng nhập <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control" required placeholder="Viết liền, không dấu">
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required placeholder="Ít nhất 6 ký tự">
                </div>
                <div class="col-md-6 form-group">
                    <label>Nhập lại <span class="text-danger">*</span></label>
                    <input type="password" name="confirm_password" class="form-control" required placeholder="Xác nhận mật khẩu">
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-block btn-auth">ĐĂNG KÝ TÀI KHOẢN</button>
        </form>

        <div class="auth-switch">
            <span>Đã có tài khoản?</span> 
            <a href="login.php<?php echo !empty($redirect) ? '?redirect='.urlencode($redirect) : ''; ?>">Đăng nhập ngay</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>