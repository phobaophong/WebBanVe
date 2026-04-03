<?php
session_start();

// 1. KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/database.php';
$thong_bao = "";

// 2. XỬ LÝ KHI SẾP THÊM TRẬN ĐẤU THỦ CÔNG
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_add_match'])) {
    $bang = $_POST['txt_bang'];
    $doi_nha = trim($_POST['txt_doi_nha']);
    $doi_khach = trim($_POST['txt_doi_khach']);
    $thoi_gian = $_POST['txt_thoi_gian'];
    $san_van_dong = trim($_POST['txt_san']);

    try {
        $sql_insert = "INSERT INTO tbl_trandau (bang_dau, doi_nha, doi_khach, thoi_gian, san_van_dong, trang_thai) 
                       VALUES (?, ?, ?, ?, ?, 'sap_dien_ra')";
        $stmt = $conn->prepare($sql_insert);
        $stmt->execute([$bang, $doi_nha, $doi_khach, $thoi_gian, $san_van_dong]);
        
        $thong_bao = "<div class='alert alert-success shadow-sm'>✅ Đã thêm trận đấu: $doi_nha vs $doi_khach thành công!</div>";
    } catch (PDOException $e) {
        $thong_bao = "<div class='alert alert-danger shadow-sm'>Lỗi: " . $e->getMessage() . "</div>";
    }
}

// 3. XỬ LÝ XÓA TRẬN ĐẤU
if (isset($_GET['delete_id'])) {
    $id_xoa = $_GET['delete_id'];
    $conn->prepare("DELETE FROM tbl_trandau WHERE id = ?")->execute([$id_xoa]);
    header("Location: manage_matches.php"); 
    exit();
}

// 4. LẤY DANH SÁCH TRẬN ĐẤU ĐỂ HIỂN THỊ RA BẢNG
$sql_get_matches = "SELECT * FROM tbl_trandau ORDER BY thoi_gian ASC";
$stmt_get = $conn->prepare($sql_get_matches);
$stmt_get->execute();
$danh_sach_tran_dau = $stmt_get->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Trận Đấu - World Cup 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-danger shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">⬅ Quay lại Bảng điều khiển</a>
        </div>
    </nav>

    <div class="container mt-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">⚽ Quản Lý Trận Đấu</h2>
            <button class="btn btn-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddMatch">
                ➕ Thêm Trận Đấu Mới
            </button>
        </div>

        <?= $thong_bao ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Bảng</th>
                            <th>Đội Nhà</th>
                            <th>Đội Khách</th>
                            <th>Thời Gian</th>
                            <th>Sân Vận Động</th>
                            <th>Trạng Thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danh_sach_tran_dau as $tran): ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($tran['bang_dau']) ?></span></td>
                            <td class="fw-bold"><?= htmlspecialchars($tran['doi_nha']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($tran['doi_khach']) ?></td>
                            <td><?= date('d/m/Y - H:i', strtotime($tran['thoi_gian'])) ?></td>
                            <td><?= htmlspecialchars($tran['san_van_dong']) ?></td>
                            <td>
                                <span class="badge bg-<?= $tran['trang_thai'] == 'sap_dien_ra' ? 'success' : 'warning' ?>">
                                    <?= $tran['trang_thai'] == 'sap_dien_ra' ? 'Sắp đá' : 'Đã kết thúc' ?>
                                </span>
                            </td>
                            <td>
                                <a href="?delete_id=<?= $tran['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa trận này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (count($danh_sach_tran_dau) == 0): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Chưa có dữ liệu trận đấu nào.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddMatch" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold">Thêm Trận Đấu Thủ Công</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bảng đấu</label>
                            <select name="txt_bang" class="form-select">
                                <?php foreach(range('A', 'H') as $l) echo "<option value='$l'>Bảng $l</option>"; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đội nhà</label>
                            <input type="text" name="txt_doi_nha" class="form-control" placeholder="Tên đội nhà..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đội khách</label>
                            <input type="text" name="txt_doi_khach" class="form-control" placeholder="Tên đội khách..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thời gian diễn ra</label>
                            <input type="datetime-local" name="txt_thoi_gian" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sân vận động</label>
                            <input type="text" name="txt_san" class="form-control" placeholder="Tên sân..." required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" name="btn_add_match" class="btn btn-primary fw-bold">Lưu Trận Đấu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>