<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$base_url = "/WEBBANVE";
$id_trandau = isset($_GET['id_trandau']) ? (int)$_GET['id_trandau'] : 0;

if ($id_trandau <= 0) {
    header("Location: index.php");
    exit();
}

$stmt_match = $conn->prepare("SELECT t.*, dn.ten_doi AS ten_nha, dk.ten_doi AS ten_khach 
                              FROM tbl_trandau t 
                              JOIN tbl_doibong dn ON t.id_doi_nha = dn.id 
                              JOIN tbl_doibong dk ON t.id_doi_khach = dk.id 
                              WHERE t.id = ?");
$stmt_match->execute([$id_trandau]);
$match = $stmt_match->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    die("Không tìm thấy trận đấu!");
}

$hangve = $conn->query("SELECT * FROM tbl_hangve ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

$stmt_ve = $conn->prepare("SELECT v.*, h.ten_hang FROM tbl_ve v JOIN tbl_hangve h ON v.id_hangve = h.id WHERE v.id_trandau = ? ORDER BY v.gia_tien ASC");
$stmt_ve->execute([$id_trandau]);
$danhsach_ve = $stmt_ve->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Vé - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">

    <header class="admin-header">
        <h1 class="admin-title">
            <img src="<?php echo $base_url; ?>/assets/images/system/icon-ticket.png" class="sys-icon" alt="icon"> QUẢN LÝ VÉ TRẬN ĐẤU
        </h1>
        <div class="admin-nav-links">
            <a href="index.php">
                <img src="<?php echo $base_url; ?>/assets/images/system/icon-back.png" class="sys-icon" alt="icon"> Trở về Bảng Điều Khiển
            </a>
        </div>
    </header>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="sidebar-box">
                    <h4 class="sidebar-heading text-primary">Tạo Vé Bán</h4>
                    
                    <?php if (isset($_SESSION['error'])) { echo "<div class='alert-error'>" . $_SESSION['error'] . "</div>"; unset($_SESSION['error']); } ?>
                    <?php if (isset($_SESSION['success_msg'])) { echo "<div class='alert alert-success alert-custom-success'>" . $_SESSION['success_msg'] . "</div>"; unset($_SESSION['success_msg']); } ?>

                    <form action="../actions/process_ticket.php" method="POST">
                        <input type="hidden" name="action_type" value="add">
                        <input type="hidden" name="id_trandau" value="<?php echo $id_trandau; ?>">

                        <div class="form-group">
                            <label class="font-weight-bold">Chọn hạng vé</label>
                            <select name="id_hangve" class="form-control" required>
                                <option value="">-- Chọn hạng --</option>
                                <?php foreach($hangve as $hv): ?>
                                    <option value="<?php echo $hv['id']; ?>"><?php echo htmlspecialchars($hv['ten_hang']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Giá tiền (VNĐ)</label>
                            <input type="number" name="gia_tien" class="form-control" min="0" required placeholder="VD: 500000">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Số lượng mở bán</label>
                            <input type="number" name="so_luong" class="form-control" min="1" required placeholder="VD: 100">
                        </div>

                        <button type="submit" class="btn btn-success btn-block font-weight-bold mt-3">MỞ BÁN VÉ NÀY</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="admin-table-container mt-0">
                    <h4 class="font-weight-bold text-danger mb-3">
                        Trận: <?php echo htmlspecialchars($match['ten_nha']); ?> <img src="<?php echo $base_url; ?>/assets/images/system/icon-flash.png" class="sys-icon" alt="icon"> <?php echo htmlspecialchars($match['ten_khach']); ?>
                    </h4>
                    <p class="text-muted">Lịch thi đấu: <?php echo date('d/m/Y H:i', strtotime($match['thoi_gian'])); ?> | Sân: <?php echo htmlspecialchars($match['san_van_dong']); ?></p>
                    
                    <table class="table table-bordered table-hover mt-3">
                        <thead class="bg-light">
                            <tr>
                                <th>Hạng Vé</th>
                                <th>Giá Bán</th>
                                <th>Số Lượng Còn</th>
                                <th class="text-center">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($danhsach_ve) > 0): ?>
                                <?php foreach($danhsach_ve as $v): ?>
                                <tr>
                                    <td class="font-weight-bold text-primary"><?php echo htmlspecialchars($v['ten_hang']); ?></td>
                                    <td class="font-weight-bold text-success"><?php echo number_format($v['gia_tien'], 0, ',', '.'); ?> đ</td>
                                    <td>
                                        <span class="badge <?php echo ($v['so_luong_con'] > 0) ? 'badge-info' : 'badge-danger'; ?>" style="font-size: 14px;">
                                            <?php echo $v['so_luong_con']; ?> vé
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="../actions/process_ticket.php?action_type=delete&id_ve=<?php echo $v['id']; ?>&id_trandau=<?php echo $id_trandau; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa hạng vé này không?');">Xóa</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Trận đấu này chưa có vé nào được mở bán. Hãy thêm vé ở cột bên trái!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>