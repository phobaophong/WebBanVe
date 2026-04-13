<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$base_url = "/WEBBANVE"; 

// Biến lưu trữ tab đang active
$active_tab = 'match'; 
$msg = "";

// ==========================================================================
// 1. XỬ LÝ LOGIC (KHÔNG TÁCH FILE, GỘP CHUNG TẠI ĐÂY)
// ==========================================================================
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_del = (int)$_GET['id'];
    try {
        // --- XÓA TRẬN ĐẤU ---
        if ($_GET['action'] == 'delete_match') {
            $stmt = $conn->prepare("DELETE FROM tbl_trandau WHERE id = :id");
            $stmt->execute(['id' => $id_del]);
            $active_tab = 'match';
        } 
        
        // --- CÀI VÉ NHANH ---
        elseif ($_GET['action'] == 'quick_ticket') {
            // 1. Kiểm tra xem trận đấu này đã có vé nào được tạo chưa
            $stmt_check = $conn->prepare("SELECT COUNT(*) FROM tbl_ve WHERE id_trandau = :id");
            $stmt_check->execute(['id' => $id_del]);
            
            if ($stmt_check->fetchColumn() > 0) {
                $msg = "<div class='alert alert-warning alert-dismissible fade show'><strong>Cảnh báo!</strong> Trận đấu này đã được cài đặt vé trước đó. Bạn hãy vào Cài vé thủ công để xem chi tiết.<button type='button' class='close' data-dismiss='alert'><span>&times;</span></button></div>";
            } else {
                // 2. Lấy danh sách hạng vé từ DB
                $hangve = $conn->query("SELECT * FROM tbl_hangve")->fetchAll(PDO::FETCH_ASSOC);
                $stmt_insert = $conn->prepare("INSERT INTO tbl_ve (id_trandau, id_hangve, gia_tien, so_luong_con) VALUES (?, ?, ?, ?)");
                
                foreach ($hangve as $hv) {
                    $ten_hang = mb_strtolower($hv['ten_hang'], 'UTF-8');
                    $so_luong = 700; // Mặc định là 700
                    $gia_tien = 200000; 

                    if (strpos($ten_hang, 'vip') !== false) {
                        $so_luong = 200;
                        $gia_tien = 1000000; // VIP 1 triệu
                    } elseif (strpos($ten_hang, 'a') !== false) {
                        $so_luong = 700;
                        $gia_tien = 500000; // Khán đài A 500k
                    } elseif (strpos($ten_hang, 'b') !== false) {
                        $so_luong = 700;
                        $gia_tien = 300000; // Khán đài B 300k
                    } elseif (strpos($ten_hang, 'c') !== false) {
                        $so_luong = 700;
                        $gia_tien = 200000; // Khán đài C 200k
                    }
                    
                    $stmt_insert->execute([$id_del, $hv['id'], $gia_tien, $so_luong]);
                }
                $msg = "<div class='alert alert-success alert-dismissible fade show'><strong>Thành công!</strong> Đã tạo nhanh 700 vé các hạng thường và 200 vé VIP.<button type='button' class='close' data-dismiss='alert'><span>&times;</span></button></div>";
            }
            $active_tab = 'match';
        }

        // --- KHÓA / MỞ KHÓA TÀI KHOẢN ---
        elseif ($_GET['action'] == 'toggle_status') {
            $stmt_status = $conn->prepare("SELECT trang_thai FROM tbl_nguoidung WHERE id = :id AND vai_tro = 'khach_hang'");
            $stmt_status->execute(['id' => $id_del]);
            $current_status = $stmt_status->fetchColumn();

            if ($current_status) {
                $new_status = ($current_status == 'hoat_dong') ? 'bi_khoa' : 'hoat_dong';
                $stmt_update = $conn->prepare("UPDATE tbl_nguoidung SET trang_thai = :new_status WHERE id = :id");
                $stmt_update->execute(['new_status' => $new_status, 'id' => $id_del]);
                $active_tab = 'user'; 
            }
        }
    } catch (PDOException $e) {
        $msg = "<div class='alert alert-danger alert-dismissible fade show'><strong>Lỗi thao tác!</strong> Dữ liệu này đang được liên kết ở nơi khác.<button type='button' class='close' data-dismiss='alert'><span>&times;</span></button></div>";
    }
}

// Bắt thông báo từ các form khác (thêm trận đấu, sửa trận đấu) chuyển về
if (isset($_SESSION['admin_msg'])) {
    $msg = $_SESSION['admin_msg'];
    unset($_SESSION['admin_msg']);
}

// ==========================================================================
// 2. TRUY VẤN DỮ LIỆU BẢNG ĐIỀU KHIỂN
// ==========================================================================
$count_match = $conn->query("SELECT COUNT(*) FROM tbl_trandau")->fetchColumn();
$count_user = $conn->query("SELECT COUNT(*) FROM tbl_nguoidung WHERE vai_tro = 'khach_hang'")->fetchColumn();
$sum_revenue = $conn->query("SELECT SUM(tong_tien) FROM tbl_donhang")->fetchColumn();
$sum_revenue = $sum_revenue ? $sum_revenue : 0;

$leagues = $conn->query("SELECT * FROM tbl_giaidau ORDER BY ten_giai ASC")->fetchAll(PDO::FETCH_ASSOC);

// Bộ lọc giải đấu
$filter_sql = "";
$filter_params = [];
if (!empty($_GET['filter_league'])) {
    $filter_sql = " WHERE t.id_giaidau = :league_id ";
    $filter_params['league_id'] = $_GET['filter_league'];
    $active_tab = 'match'; 
}

$sql_matches = "SELECT t.id, t.thoi_gian, t.trang_thai, g.ten_giai, dn.ten_doi AS ten_nha, dk.ten_doi AS ten_khach 
                FROM tbl_trandau t
                JOIN tbl_giaidau g ON t.id_giaidau = g.id
                JOIN tbl_doibong dn ON t.id_doi_nha = dn.id
                JOIN tbl_doibong dk ON t.id_doi_khach = dk.id
                $filter_sql
                ORDER BY t.thoi_gian DESC";
$stmt_matches = $conn->prepare($sql_matches);
$stmt_matches->execute($filter_params);
$matches = $stmt_matches->fetchAll(PDO::FETCH_ASSOC);

$sql_users = "SELECT * FROM tbl_nguoidung WHERE vai_tro = 'khach_hang' ORDER BY created_at DESC";
$users = $conn->query($sql_users)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">

    <header class="admin-header">
        <h1 class="admin-title">🛠️ BẢNG ĐIỀU KHIỂN ADMIN</h1>
        <div class="admin-nav-links">
            <span class="text-warning font-weight-bold mr-3">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="scan_ticket.php" class="text-warning font-weight-bold mr-3" style="font-size: 18px;">📷 Soát Vé QR</a>
            <a href="<?php echo $base_url; ?>/index.php">🌐 Trở về Website</a>
            <a href="<?php echo $base_url; ?>/actions/process_logout.php" class="text-danger">Đăng xuất</a>
        </div>
    </header>

    <div class="container mt-4">
        <?php echo $msg; ?>

        <div class="row mb-4">
            <div class="col-md-4 mb-3"><div class="stat-card bg-blue d-flex justify-content-between align-items-center"><div><h5 class="font-weight-bold">TỔNG TRẬN ĐẤU</h5><h2><?php echo $count_match; ?></h2></div><div class="stat-icon">⚽</div></div></div>
            <div class="col-md-4 mb-3"><div class="stat-card bg-green d-flex justify-content-between align-items-center"><div><h5 class="font-weight-bold">TỔNG KHÁCH HÀNG</h5><h2><?php echo $count_user; ?></h2></div><div class="stat-icon">👥</div></div></div>
            <div class="col-md-4 mb-3"><div class="stat-card bg-red d-flex justify-content-between align-items-center"><div><h5 class="font-weight-bold">TỔNG DOANH THU</h5><h2><?php echo number_format($sum_revenue, 0, ',', '.'); ?>đ</h2></div><div class="stat-icon">💰</div></div></div>
        </div>

        <div class="admin-table-container">
            <ul class="nav nav-tabs admin-tabs" id="adminTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($active_tab == 'match') ? 'active' : ''; ?>" data-toggle="tab" href="#match">QUẢN LÝ TRẬN ĐẤU</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($active_tab == 'user') ? 'active' : ''; ?>" data-toggle="tab" href="#user">QUẢN LÝ KHÁCH HÀNG</a>
                </li>
            </ul>

            <div class="tab-content" id="adminTabContent">
                
                <div class="tab-pane fade <?php echo ($active_tab == 'match') ? 'show active' : ''; ?>" id="match">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="font-weight-bold text-secondary mb-0">Danh sách trận đấu</h5>
                        <div class="d-flex align-items-center">
                            <form action="index.php" method="GET" class="form-inline mr-3">
                                <select name="filter_league" class="form-control" onchange="this.form.submit()">
                                    <option value="">-- Tất cả giải đấu --</option>
                                    <?php foreach($leagues as $lg): ?>
                                        <option value="<?php echo $lg['id']; ?>" <?php echo (isset($_GET['filter_league']) && $_GET['filter_league'] == $lg['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($lg['ten_giai']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                            <a href="add_match.php" class="btn btn-success font-weight-bold">+ Thêm Trận Đấu</a>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-admin border">
                            <thead>
                                <tr><th>ID</th><th>Giải Đấu</th><th>Trận Đấu</th><th>Thời Gian</th><th>Trạng Thái</th><th class="text-center">Hành Động</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach($matches as $m): ?>
                                <tr>
                                    <td><b>#<?php echo $m['id']; ?></b></td>
                                    <td><?php echo htmlspecialchars($m['ten_giai']); ?></td>
                                    <td class="font-weight-bold text-primary"><?php echo htmlspecialchars($m['ten_nha']); ?> - <?php echo htmlspecialchars($m['ten_khach']); ?></td>
                                    <td><?php echo date('H:i d/m/Y', strtotime($m['thoi_gian'])); ?></td>
                                    <td>
                                        <?php 
                                            if($m['trang_thai'] == 'sap_dien_ra') echo "<span class='badge badge-success'>Sắp diễn ra</span>";
                                            elseif($m['trang_thai'] == 'dang_da') echo "<span class='badge badge-warning'>Đang đá</span>";
                                            else echo "<span class='badge badge-secondary'>Đã kết thúc</span>";
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($m['trang_thai'] == 'sap_dien_ra'): ?>
                                            <a href="manage_tickets.php?id_trandau=<?php echo $m['id']; ?>" class="text-warning font-weight-bold mr-2" title="Cài đặt vé thủ công">🎟️ Cài vé</a>
                                            <a href="index.php?action=quick_ticket&id=<?php echo $m['id']; ?>" class="text-success font-weight-bold mr-3" onclick="return confirm('Hệ thống sẽ tự tạo 700 vé thường và 200 vé VIP. Bạn có chắc chắn?');" title="Auto tạo vé (700 Thường, 200 VIP)">⚡ Nhanh</a>
                                        <?php endif; ?>
                                        
                                        <a href="edit_match.php?id=<?php echo $m['id']; ?>" class="text-info font-weight-bold mr-2">✏️ Sửa</a>
                                        <a href="index.php?action=delete_match&id=<?php echo $m['id']; ?>" class="text-danger font-weight-bold" onclick="return confirm('Bạn có chắc chắn xóa trận đấu này?');">🗑️ Xóa</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade <?php echo ($active_tab == 'user') ? 'show active' : ''; ?>" id="user">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="font-weight-bold text-secondary mb-0">Danh sách khách hàng</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-admin border">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Họ & Tên</th>
                                    <th>Tên Đăng Nhập</th>
                                    <th>Liên Hệ</th>
                                    <th>Số Dư</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-center">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $u): ?>
                                <tr>
                                    <td><b>#<?php echo $u['id']; ?></b></td>
                                    <td class="font-weight-bold"><?php echo htmlspecialchars($u['ho_ten']); ?></td>
                                    <td><?php echo htmlspecialchars($u['ten_dang_nhap']); ?></td>
                                    <td><div>✉️ <?php echo htmlspecialchars($u['email']); ?></div><div>📞 <?php echo htmlspecialchars($u['sdt']); ?></div></td>
                                    <td class="font-weight-bold text-danger"><?php echo number_format($u['so_du'], 0, ',', '.'); ?>đ</td>
                                    
                                    <td>
                                        <?php if(isset($u['trang_thai']) && $u['trang_thai'] == 'hoat_dong'): ?>
                                            <span class="badge badge-success">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Bị khóa</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if(isset($u['trang_thai']) && $u['trang_thai'] == 'hoat_dong'): ?>
                                            <a href="index.php?action=toggle_status&id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-danger font-weight-bold" onclick="return confirm('Bạn có chắc muốn KHÓA tài khoản này?');">🔒 Khóa</a>
                                        <?php else: ?>
                                            <a href="index.php?action=toggle_status&id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-success font-weight-bold" onclick="return confirm('Xác nhận MỞ KHÓA cho tài khoản này?');">🔓 Mở khóa</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>