<?php
// BẮT BUỘC: Khởi tạo session để kiểm tra thẻ nhớ
session_start();

// ==========================================
// BỨC TƯỜNG LỬA BẢO MẬT (RẤT QUAN TRỌNG)
// ==========================================
// Kiểm tra xem người vào đây đã đăng nhập chưa, VÀ có phải là admin không.
// Nếu là khách hàng bình thường tò mò gõ link /admin, hệ thống sẽ đá văng ra trang chủ ngay lập tức!
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">⚙️ ADMIN CONTROL PANEL</a>
            
            <div class="d-flex text-white align-items-center">
                <span class="me-4 fw-bold">Xin chào sếp, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <a href="../actions/process_logout.php" class="btn btn-sm btn-outline-light">Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 fw-bold border-bottom pb-2">Tổng Quan Hệ Thống</h2>
        
        <div class="row g-4">
            
            <div class="col-md-4">
                <div class="card text-white bg-primary h-100 shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <h1 class="display-4">⚽</h1>
                        <h4 class="card-title fw-bold mt-3">Quản Lý Trận Đấu</h4>
                        <p class="card-text">Thêm, sửa, xóa lịch thi đấu.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 mb-3 text-center">
                        <a href="manage_matches.php" class="btn btn-light w-75 fw-bold text-primary">Vào Quản Lý</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success h-100 shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <h1 class="display-4">🎫</h1>
                        <h4 class="card-title fw-bold mt-3">Quản Lý Vé & Đơn Hàng</h4>
                        <p class="card-text">Xem danh sách vé đã bán, doanh thu.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 mb-3 text-center">
                        <a href="#" class="btn btn-light w-75 fw-bold text-success">Vào Quản Lý</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-warning h-100 shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <h1 class="display-4">👥</h1>
                        <h4 class="card-title fw-bold mt-3 text-dark">Quản Lý Khách Hàng</h4>
                        <p class="card-text text-dark">Xem danh sách tài khoản, cộng trừ tiền.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 mb-3 text-center">
                        <a href="#" class="btn btn-dark w-75 fw-bold text-warning">Vào Quản Lý</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>