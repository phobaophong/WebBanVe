<?php
session_start();
// Nếu đã đăng nhập rồi thì đá về trang chủ
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/database.php'; 
include '../includes/header.php';
include '../includes/navbar.php';

$error = "";
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $redirect_url = $_POST['redirect'];

    if (empty($username) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ tài khoản và mật khẩu!";
    } else {
        // Truy vấn theo bảng tbl_nguoidung của database
        $stmt = $conn->prepare("SELECT * FROM tbl_nguoidung WHERE ten_dang_nhap = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kiểm tra mật khẩu mã hóa
        if ($user && password_verify($password, $user['mat_khau'])) {
            // Lưu Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['ten_dang_nhap'];
            $_SESSION['role'] = $user['vai_tro']; // 'admin' hoặc 'khach_hang'

            // Xử lý chuyển hướng
            if (!empty($redirect_url)) {
                if (strpos($redirect_url, 'checkout.php') !== false) {
                    header("Location: " . $redirect_url);
                } else {
                    header("Location: ../" . $redirect_url);
                }
            } else {
                header("Location: ../index.php"); 
            }
            exit();
        } else {
            $error = "Tài khoản hoặc mật khẩu không chính xác!";
        }
    }
}
?>

<div class="container">
    <div class="auth-container">
        <h2 class="auth-title">🔒 ĐĂNG NHẬP</h2>
        
        <?php 
        if (isset($_SESSION['error'])) {
            echo "<div class='alert-error'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']); 
        }
        if (!empty($error)) {
            echo "<div class='alert-error'>" . $error . "</div>";
        }
        if (isset($_SESSION['success_msg'])) {
            echo "<div class='alert alert-success alert-custom-success'>" . $_SESSION['success_msg'] . "</div>";
            unset($_SESSION['success_msg']);
        }
        ?>

        <form action="login.php" method="POST">
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