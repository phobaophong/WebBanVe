<?php 
session_start();
include '../includes/header.php'; 
include '../includes/navbar.php'; 
?>

<div class="container auth-container">
    <h2 class="auth-title">Đăng Ký Tài Khoản</h2>
    
    <?php 
    if(isset($_SESSION['error'])) {
        echo "<div class='alert-error'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']);
    }
    ?>

    <form action="../actions/process_register.php" method="POST">
        <div class="form-group">
            <label>Tên đăng nhập (*):</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mật khẩu (*):</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Họ và tên:</label>
            <input type="text" name="fullname" class="form-control">
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label>Số điện thoại:</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Đăng Ký</button>
    </form>
    
    <p class="text-center" style="margin-top: 15px;">Đã có tài khoản? <a href="login.php" style="color: #1E90FF; font-weight: bold;">Đăng nhập</a></p>
</div>

<?php include '../includes/footer.php'; ?>