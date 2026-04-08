<?php 
session_start();
// Nếu khách đã đăng nhập rồi thì đá văng ra trang chủ, không cho vào trang login nữa
if(isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/header.php'; 
include '../includes/navbar.php'; 
?>

<div class="container auth-container">
    <h2 class="auth-title">Đăng Nhập</h2>
    
    <?php 
    // Hiển thị thông báo lỗi (nếu nhập sai pass/user)
    if(isset($_SESSION['error'])) {
        echo "<div class='alert-error'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']);
    }
    // Hiển thị thông báo thành công (nếu vừa đăng ký xong chuyển qua)
    if(isset($_SESSION['success'])) {
        echo "<div style='color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 4px; text-align: center; margin-bottom: 15px; font-size: 14px;'>" . $_SESSION['success'] . "</div>";
        unset($_SESSION['success']);
    }
    ?>

    <form action="../actions/process_login.php" method="POST">
        <div class="form-group">
            <label>Tên đăng nhập:</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="form-group">
            <label>Mật khẩu:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Đăng Nhập</button>
    </form>
    
    <p class="text-center" style="margin-top: 15px;">Chưa có tài khoản? <a href="register.php" style="color: #1E90FF; font-weight: bold;">Đăng ký ngay</a></p>
</div>

<?php include '../includes/footer.php'; ?>