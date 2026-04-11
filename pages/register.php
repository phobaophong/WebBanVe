<?php
session_start();
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
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $redirect_url = $_POST['redirect'];

    if (empty($username) || empty($password) || empty($fullname) || empty($email) || empty($phone)) {
        $error = "Vui lòng nhập đầy đủ các trường bắt buộc!";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp!";
    } elseif (strlen($password) < 6) {
        $error = "Mật khẩu phải chứa ít nhất 6 ký tự!";
    } else {
        // Kiểm tra trùng lặp User, Email hoặc Số điện thoại
        $stmt_check = $conn->prepare("SELECT id FROM tbl_nguoidung WHERE ten_dang_nhap = :username OR email = :email OR sdt = :phone");
        $stmt_check->execute([
            'username' => $username, 
            'email' => $email,
            'phone' => $phone
        ]);
        
        if ($stmt_check->rowCount() > 0) {
            $error = "Tên đăng nhập, Email hoặc Số điện thoại này đã được sử dụng!";
        } else {
            // Mã hóa Bcrypt
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Thêm vào cơ sở dữ liệu bảng tbl_nguoidung
            $sql_insert = "INSERT INTO tbl_nguoidung (ho_ten, email, sdt, ten_dang_nhap, mat_khau, vai_tro) 
                           VALUES (:fullname, :email, :phone, :username, :password, 'khach_hang')";
            $stmt_insert = $conn->prepare($sql_insert);
            $result = $stmt_insert->execute([
                'fullname' => $fullname,
                'email' => $email,
                'phone' => $phone,
                'username' => $username,
                'password' => $hashed_password
            ]);

            if ($result) {
                $_SESSION['success_msg'] = "🎉 Đăng ký thành công! Vui lòng đăng nhập.";
                $redirect_query = !empty($redirect_url) ? "?redirect=" . urlencode($redirect_url) : "";
                header("Location: login.php" . $redirect_query);
                exit();
            } else {
                $error = "Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau!";
            }
        }
    }
}
?>

<div class="container">
    <div class="auth-container" style="max-width: 500px;"> <h2 class="auth-title">📝 TẠO TÀI KHOẢN</h2>
        
        <?php if (!empty($error)): ?>
            <div class='alert-error'><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">

            <div class="form-group">
                <label>Họ và tên đầy đủ <span class="text-danger">*</span></label>
                <input type="text" name="fullname" class="form-control" required placeholder="VD: Nguyễn Văn A" value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" required placeholder="email@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="col-md-6 form-group">
                    <label>Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" required placeholder="VD: 0987654321" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Tên đăng nhập <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control" required placeholder="Viết liền, không dấu" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
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