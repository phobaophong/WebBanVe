<?php
session_start();

// Lưu ý: File này nằm trong thư mục pages/ nên phải lùi lại 1 bước (../) để gọi config
require_once '../config/database.php';

// Lấy danh sách tất cả các trận đấu sắp diễn ra từ Database
$sql = "SELECT * FROM tbl_trandau WHERE trang_thai = 'sap_dien_ra' ORDER BY thoi_gian ASC";
$stmt = $conn->query($sql);
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Trận Đấu - World Cup 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .match-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 15px;
        }

        .match-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .vs-text {
            font-size: 1.2rem;
            font-style: italic;
            color: #dc3545;
            margin: 0 10px;
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-danger" href="../index.php">⬅ VỀ TRANG CHỦ</a>
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-white me-3">💰 Số dư: <b
                            class="text-warning"><?= number_format($_SESSION['balance'] ?? 10000000, 0, ',', '.') ?>
                            VNĐ</b></span>
                    <span class="text-light me-3">Chào, <b><?= htmlspecialchars($_SESSION['user_name']) ?></b></span>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="../admin/index.php" class="btn btn-sm btn-outline-info me-2">Admin Panel</a>
                    <?php endif; ?>
                    <a href="../actions/process_logout.php" class="btn btn-sm btn-danger">Đăng xuất</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light me-2">Đăng nhập</a>
                    <a href="register.php" class="btn btn-danger">Đăng ký (Tặng 10tr)</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="bg-danger text-white text-center py-4 mb-5 shadow-sm">
        <h2 class="fw-bold m-0">LỊCH THI ĐẤU & MUA VÉ</h2>
    </div>

    <div class="container pb-5">
        <div class="row g-4">
            <?php foreach ($matches as $m): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card match-card h-100 shadow-sm border-0 overflow-hidden">
                        <div class="card-header bg-dark text-white text-center fw-bold py-2">
                            BẢNG <?= $m['bang_dau'] ?>
                        </div>

                        <div class="card-body text-center mt-2">
                            <h4 class="fw-bold text-primary mb-3">
                                <?= htmlspecialchars($m['doi_nha']) ?>
                                <span class="vs-text">VS</span>
                                <?= htmlspecialchars($m['doi_khach']) ?>
                            </h4>
                            <div class="text-muted small mb-3">
                                <p class="mb-1">🕒 <?= date('d/m/Y - H:i', strtotime($m['thoi_gian'])) ?></p>
                                <p class="mb-0">📍 <?= htmlspecialchars($m['san_van_dong']) ?></p>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 text-center pb-4 pt-0">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="checkout.php?id_trandau=<?= $m['id'] ?>"
                                    class="btn btn-danger fw-bold rounded-pill w-75 shadow-sm">
                                    🎟️ CHỌN MUA VÉ
                                </a>
                            <?php else: ?>
                                <a href="login.php?error=need_login"
                                    class="btn btn-secondary fw-bold rounded-pill w-75 shadow-sm"
                                    onclick="alert('Vui lòng đăng nhập hoặc đăng ký tài khoản để mua vé bro nhé!');">
                                    🔒 ĐĂNG NHẬP ĐỂ MUA
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($matches)): ?>
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">Hiện chưa có trận đấu nào được mở bán.</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>