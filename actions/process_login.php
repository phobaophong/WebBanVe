<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!";
        header("Location: ../pages/login.php");
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_nguoidung WHERE ten_dang_nhap = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mat_khau'])) {
            if ($user['trang_thai'] === 'bi_khoa') {
                $_SESSION['error'] = "Tài khoản của bạn đã bị khóa do vi phạm chính sách!";
                header("Location: ../pages/login.php");
                exit();
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['ten_dang_nhap'];
            $_SESSION['ho_ten'] = $user['ho_ten']; 
            $_SESSION['so_du'] = $user['so_du'];
            $_SESSION['role'] = $user['vai_tro'];
            
            // Chuyển hướng
            if ($user['vai_tro'] === 'admin') {
                header("Location: ../admin/index.php"); // Nếu là admin 
            } else {
                header("Location: ../index.php"); // Khách hàng 
            }
            exit();
        } else {
            $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không chính xác!";
            header("Location: ../pages/login.php");
            exit();
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
        header("Location: ../pages/login.php");
        exit();
    }
} else {
    header("Location: ../pages/login.php");
    exit();
}
?>