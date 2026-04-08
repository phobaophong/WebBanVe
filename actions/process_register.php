<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Tên đăng nhập và mật khẩu không được để trống!";
        header("Location: ../pages/register.php");
        exit();
    }

    try {
        $check_stmt = $conn->prepare("SELECT id FROM tbl_nguoidung WHERE ten_dang_nhap = :username OR email = :email");
        $check_stmt->execute(['username' => $username, 'email' => $email]);

        if ($check_stmt->rowCount() > 0) {
            $_SESSION['error'] = "Tên đăng nhập hoặc Email đã được sử dụng!";
            header("Location: ../pages/register.php");
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_stmt = $conn->prepare("INSERT INTO tbl_nguoidung (ten_dang_nhap, mat_khau, ho_ten, email, sdt) VALUES (:username, :password, :fullname, :email, :phone)");
        
        $inserted = $insert_stmt->execute([
            'username' => $username,
            'password' => $hashed_password,
            'fullname' => $fullname,
            'email'    => $email,
            'phone'    => $phone
        ]);

        if ($inserted) {
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'khach_hang';
            
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['error'] = "Đã xảy ra lỗi khi tạo tài khoản!";
            header("Location: ../pages/register.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
        header("Location: ../pages/register.php");
        exit();
    }

} else {
    header("Location: ../pages/register.php");
    exit();
}
?>