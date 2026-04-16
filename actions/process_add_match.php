<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action_type = $_POST['action_type'];


    if ($action_type == 'manual') {
        $id_giaidau = (int)$_POST['id_giaidau'];
        $id_doi_nha = (int)$_POST['id_doi_nha'];
        $id_doi_khach = (int)$_POST['id_doi_khach'];
        $thoi_gian = $_POST['thoi_gian'];
        $trang_thai = $_POST['trang_thai'];
        $san_van_dong = trim($_POST['san_van_dong']);

        // Check trùng đội bóng
        if ($id_doi_nha === $id_doi_khach) {
            $_SESSION['error'] = "Đội nhà và Đội khách không được giống nhau!";
            header("Location: ../admin/add_match.php");
            exit();
        }

        try {
            $sql = "INSERT INTO tbl_trandau (id_giaidau, id_doi_nha, id_doi_khach, thoi_gian, san_van_dong, trang_thai) 
                    VALUES (:lg, :nha, :khach, :tg, :svd, :tt)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'lg' => $id_giaidau, 'nha' => $id_doi_nha, 'khach' => $id_doi_khach,
                'tg' => $thoi_gian, 'svd' => $san_van_dong, 'tt' => $trang_thai
            ]);

            $_SESSION['admin_msg'] = "<div class='alert alert-success alert-custom-success'>✅ Đã thêm trận đấu thành công!</div>";
            header("Location: ../admin/index.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi Database: " . $e->getMessage();
            header("Location: ../admin/add_match.php");
            exit();
        }
    }


    elseif ($action_type == 'csv') {
        if (isset($_FILES['file_csv']) && $_FILES['file_csv']['error'] == 0) {
            $file_tmp = $_FILES['file_csv']['tmp_name'];
            $success_count = 0;

            try {
                $conn->beginTransaction();
                $stmt = $conn->prepare("INSERT INTO tbl_trandau (id_giaidau, id_doi_nha, id_doi_khach, thoi_gian, san_van_dong, trang_thai) VALUES (?, ?, ?, ?, ?, ?)");

                if (($handle = fopen($file_tmp, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        // Cấu trúc mong muốn: [0]id_giaidau, [1]id_nha, [2]id_khach, [3]thoi_gian, [4]san_van_dong, [5]trang_thai
                        if (count($data) >= 6 && is_numeric($data[0])) {
                            // Insert từng dòng
                            $stmt->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5]]);
                            $success_count++;
                        }
                    }
                    fclose($handle);
                }
                
                $conn->commit();
                $_SESSION['admin_msg'] = "<div class='alert alert-success alert-custom-success'>✅ Đã import thành công $success_count trận đấu từ file CSV!</div>";
                header("Location: ../admin/index.php");
                exit();

            } catch (Exception $e) {
                $conn->rollBack();
                $_SESSION['error'] = "Lỗi khi đọc file CSV: Đảm bảo cấu trúc file hợp lệ. Lỗi chi tiết: " . $e->getMessage();
                header("Location: ../admin/add_match.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "File tải lên bị lỗi hoặc không tồn tại.";
            header("Location: ../admin/add_match.php");
            exit();
        }
    }
} else {
    header("Location: ../admin/index.php");
    exit();
}
?>