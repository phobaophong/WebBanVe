<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$base_url = "/WEBBANVE";
$id_trandau = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_trandau <= 0) {
    header("Location: index.php");
    exit();
}

// 1. Lấy thông tin trận đấu cần sửa
$stmt_match = $conn->prepare("SELECT * FROM tbl_trandau WHERE id = ?");
$stmt_match->execute([$id_trandau]);
$match = $stmt_match->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    die("<div class='container mt-5'><h3 class='text-danger text-center'>Không tìm thấy trận đấu!</h3></div>");
}

// 2. KIỂM TRA ĐIỀU KIỆN KHÓA (Đã có vé được tạo chưa?)
// Nếu Admin đã cài đặt vé cho trận này, tuyệt đối không cho sửa Tên đội và Giải đấu nữa
$stmt_check_ticket = $conn->prepare("SELECT COUNT(*) FROM tbl_ve WHERE id_trandau = ?");
$stmt_check_ticket->execute([$id_trandau]);
$da_co_ve = ($stmt_check_ticket->fetchColumn() > 0);

// Lấy danh sách Giải đấu & Đội bóng
$leagues = $conn->query("SELECT * FROM tbl_giaidau ORDER BY ten_giai ASC")->fetchAll(PDO::FETCH_ASSOC);
$teams = $conn->query("SELECT * FROM tbl_doibong ORDER BY ten_doi ASC")->fetchAll(PDO::FETCH_ASSOC);

// Định dạng lại thời gian để hiển thị đúng trong thẻ <input type="datetime-local">
$thoi_gian_format = date('Y-m-d\TH:i', strtotime($match['thoi_gian']));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Trận Đấu - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">

    <header class="admin-header">
        <h1 class="admin-title">✏️ CẬP NHẬT TRẬN ĐẤU #<?php echo $id_trandau; ?></h1>
        <div class="admin-nav-links">
            <a href="index.php">🔙 Trở về Bảng Điều Khiển</a>
        </div>
    </header>

    <div class="container mt-5">
        <div class="admin-table-container" style="max-width: 800px; margin: auto;">
            
            <?php if ($da_co_ve): ?>
                <div class="alert alert-warning font-weight-bold">
                    ⚠️ Trận đấu này đã được thiết lập vé bán. Hệ thống đã khóa chức năng sửa Giải đấu và Đội bóng để đảm bảo quyền lợi khách hàng.
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])) { echo "<div class='alert-error'>" . $_SESSION['error'] . "</div>"; unset($_SESSION['error']); } ?>

            <form action="../actions/process_edit_match.php" method="POST">
                <input type="hidden" name="id_trandau" value="<?php echo $match['id']; ?>">
                
                <input type="hidden" name="is_locked" value="<?php echo $da_co_ve ? '1' : '0'; ?>">

                <h5 class="text-primary font-weight-bold mb-3 border-bottom pb-2">1. THÔNG TIN CỐT LÕI</h5>
                
                <div class="form-group">
                    <label class="font-weight-bold">Giải Đấu</label>
                    <select name="id_giaidau" id="leagueSelect" class="form-control" <?php echo $da_co_ve ? 'disabled' : 'required'; ?>>
                        <option value="">-- Chọn Giải đấu --</option>
                        <?php foreach($leagues as $lg): ?>
                            <option value="<?php echo $lg['id']; ?>" <?php echo ($match['id_giaidau'] == $lg['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($lg['ten_giai']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold text-danger">Đội Nhà</label>
                        <select name="id_doi_nha" id="homeTeam" class="form-control" <?php echo $da_co_ve ? 'disabled' : 'required'; ?>>
                            <?php foreach($teams as $t): ?>
                                <option value="<?php echo $t['id']; ?>" <?php echo ($match['id_doi_nha'] == $t['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($t['ten_doi']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold text-primary">Đội Khách</label>
                        <select name="id_doi_khach" id="awayTeam" class="form-control" <?php echo $da_co_ve ? 'disabled' : 'required'; ?>>
                            <?php foreach($teams as $t): ?>
                                <option value="<?php echo $t['id']; ?>" <?php echo ($match['id_doi_khach'] == $t['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($t['ten_doi']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <h5 class="text-success font-weight-bold mb-3 mt-4 border-bottom pb-2">2. THÔNG TIN CẬP NHẬT</h5>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">Thời gian thi đấu (Có thể dời lịch)</label>
                        <input type="datetime-local" name="thoi_gian" class="form-control" value="<?php echo $thoi_gian_format; ?>" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">Trạng thái (Ghi đè thủ công)</label>
                        <select name="trang_thai" class="form-control" required>
                            <option value="sap_dien_ra" <?php echo ($match['trang_thai'] == 'sap_dien_ra') ? 'selected' : ''; ?>>Sắp diễn ra</option>
                            <option value="dang_da" <?php echo ($match['trang_thai'] == 'dang_da') ? 'selected' : ''; ?>>Đang đá</option>
                            <option value="da_ket_thuc" <?php echo ($match['trang_thai'] == 'da_ket_thuc') ? 'selected' : ''; ?>>Đã kết thúc</option>
                            <option value="da_huy" <?php echo ($match['trang_thai'] == 'da_huy') ? 'selected' : ''; ?>>Đã hủy (Hoãn)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Sân vận động</label>
                    <input type="text" name="san_van_dong" class="form-control" value="<?php echo htmlspecialchars($match['san_van_dong']); ?>" required>
                </div>

                <button type="submit" class="btn btn-warning btn-block btn-auth font-weight-bold mt-4 text-dark">LƯU THAY ĐỔI</button>
            </form>

        </div>
    </div>
</body>
</html>