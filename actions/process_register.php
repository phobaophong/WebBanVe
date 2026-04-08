<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy và làm sạch dữ liệu đầu vào
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = $_POST['password'];
    $fullname = $conn->real_escape_string(trim($_POST['fullname']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Tên đăng nhập và mật khẩu không được để trống!";
        header("Location: ../pages/register.php");
        exit();
    }

    // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    $check_sql = "SELECT id FROM tbl_nguoidung WHERE ten_dang_nhap = '$username' OR email = '$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Tên đăng nhập hoặc Email đã được sử dụng!";
        header("Location: ../pages/register.php");
        exit();
    }

    // Mã hóa mật khẩu bảo mật
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Chèn dữ liệu (Mặc định vai_tro là 'khach_hang', so_du là 0 theo cấu trúc DB)
    $sql = "INSERT INTO tbl_nguoidung (ten_dang_nhap, mat_khau, ho_ten, email, sdt) 
            VALUES ('$username', '$hashed_password', '$fullname', '$email', '$phone')";

    if ($conn->query($sql) === TRUE) {
        // Đăng ký thành công, tạo session đăng nhập luôn cho khách
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'khach_hang';
        
        echo "<script>alert('Đăng ký thành công!'); window.location.href='../index.php';</script>";
        exit();
    } else {
        $_SESSION['error'] = "Lỗi hệ thống: " . $conn->error;
        header("Location: ../pages/register.php");
        exit();
    }
} else {
    header("Location: ../pages/register.php");
    exit();
}
?>