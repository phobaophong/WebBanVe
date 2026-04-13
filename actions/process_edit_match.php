<?php
session_start();
require_once '../config/database.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_trandau = (int)$_POST['id_trandau'];
    $is_locked = $_POST['is_locked'] == '1'; // Kiểm tra cờ khóa
    
    // Các thông tin luôn được phép sửa
    $thoi_gian = $_POST['thoi_gian'];
    $trang_thai = $_POST['trang_thai'];
    $san_van_dong = trim($_POST['san_van_dong']);

    try {
        if ($is_locked) {
            // TRƯỜNG HỢP B: Bị khóa -> Chỉ cập nhật Thời gian, Trạng thái, Sân
            $sql = "UPDATE tbl_trandau 
                    SET thoi_gian = :thoi_gian, trang_thai = :trang_thai, san_van_dong = :san_van_dong 
                    WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'thoi_gian' => $thoi_gian,
                'trang_thai' => $trang_thai,
                'san_van_dong' => $san_van_dong,
                'id' => $id_trandau
            ]);
        } else {
            // TRƯỜNG HỢP A: Chưa bị khóa -> Cho phép cập nhật cả Đội bóng và Giải đấu
            $id_giaidau = (int)$_POST['id_giaidau'];
            $id_doi_nha = (int)$_POST['id_doi_nha'];
            $id_doi_khach = (int)$_POST['id_doi_khach'];

            // Kiểm tra trùng đội
            if ($id_doi_nha === $id_doi_khach) {
                $_SESSION['error'] = "Đội nhà và Đội khách không được trùng nhau!";
                header("Location: ../admin/edit_match.php?id=" . $id_trandau);
                exit();
            }

            $sql = "UPDATE tbl_trandau 
                    SET id_giaidau = :id_giaidau, id_doi_nha = :id_doi_nha, id_doi_khach = :id_doi_khach, 
                        thoi_gian = :thoi_gian, trang_thai = :trang_thai, san_van_dong = :san_van_dong 
                    WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'id_giaidau' => $id_giaidau,
                'id_doi_nha' => $id_doi_nha,
                'id_doi_khach' => $id_doi_khach,
                'thoi_gian' => $thoi_gian,
                'trang_thai' => $trang_thai,
                'san_van_dong' => $san_van_dong,
                'id' => $id_trandau
            ]);
        }

        // Báo thành công và trả về trang Admin
        $_SESSION['admin_msg'] = "<div class='alert alert-success alert-dismissible fade show'><strong>Cập nhật thành công!</strong> Đã sửa thông tin trận đấu #$id_trandau.<button type='button' class='close' data-dismiss='alert'><span>&times;</span></button></div>";
        header("Location: ../admin/index.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi Database: " . $e->getMessage();
        header("Location: ../admin/edit_match.php?id=" . $id_trandau);
        exit();
    }
} else {
    header("Location: ../admin/index.php");
    exit();
}
?>