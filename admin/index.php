<?php
session_start();
require_once '../config/database.php';

// 1. KIỂM TRA QUYỀN ADMIN TỐI THƯỢNG
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Nếu chưa đăng nhập hoặc không phải admin -> Đuổi về trang chủ
    header("Location: ../index.php");
    exit();
}

$base_url = "/WEBBANVE"; 

// 2. XỬ LÝ LOGIC XÓA (NẾU CÓ YÊU CẦU TỪ NÚT XÓA)
$msg = "";
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_del = (int)$_GET['id'];
    try {
        if ($_GET['action'] == 'delete_match') {
            $stmt = $conn->prepare("DELETE FROM tbl_trandau WHERE id = :id");
            $stmt->execute(['id' => $id_del]);
            $msg = "<div class='alert alert-success alert-custom-success'>✅ Đã xóa trận đấu thành công!</div>";
        } elseif ($_GET['action'] == 'delete_user') {
            $stmt = $conn->prepare("DELETE FROM tbl_nguoidung WHERE id = :id AND vai_tro = 'khach_hang'");
            $stmt->execute(['id' => $id_del]);
            $msg = "<div class='alert alert-success alert-custom-success'>✅ Đã xóa khách hàng thành công!</div>";
        }
    } catch (PDOException $e) {
        $msg = "<div class='alert alert-danger'>❌ Không thể xóa vì dữ liệu này đang dính dáng đến Đơn hàng (Khóa ngoại).</div>";
    }
}

// 3. TRUY VẤN DỮ LIỆU ĐỂ HIỂN THỊ
// - Thống kê
$count_match = $conn->query("SELECT COUNT(*) FROM tbl_trandau")->fetchColumn();
$count_user = $conn->query("SELECT COUNT(*) FROM tbl_nguoidung WHERE vai_tro = 'khach_hang'")->fetchColumn();
$sum_revenue = $conn->query("SELECT SUM(tong_tien) FROM tbl_donhang")->fetchColumn();
$sum_revenue = $sum_revenue ? $sum_revenue : 0;

// - Lấy danh sách trận đấu
$sql_matches = "SELECT t.id, t.thoi_gian, t.trang_thai, g.ten_giai, dn.ten_doi AS ten_nha, dk.ten_doi AS ten_khach 
                FROM tbl_trandau t
                JOIN tbl_giaidau g ON t.id_giaidau = g.id
                JOIN tbl_doibong dn ON t.id_doi_nha = dn.id
                JOIN tbl_doibong dk ON t.id_doi_khach = dk.id
                ORDER BY t.thoi_gian DESC";
$matches = $conn->query($sql_matches)->fetchAll(PDO::FETCH_ASSOC);

// - Lấy danh sách khách hàng
$sql_users = "SELECT * FROM tbl_nguoidung WHERE vai_tro = 'khach_hang' ORDER BY created_at DESC";
$users = $conn->query($sql_users)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khu Vực Quản Trị - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">

    <header class="admin-header">
        <h1 class="admin-title">🛠️ BẢNG ĐIỀU KHIỂN ADMIN</h1>
        <div class="admin-nav-links">
            <span class="text-warning font-weight-bold mr-3">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="<?php echo $base_url; ?>/index.php">🌐 Trở về Website</a>
            <a href="<?php echo $base_url; ?>/actions/process_logout.php" class="text-danger">Đăng xuất</a>
        </div>
    </header>

    <div class="container mt-4">
        <?php echo $msg; ?>

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card bg-blue d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-bold">TỔNG TRẬN ĐẤU</h5>
                        <h2><?php echo $count_match; ?></h2>
                    </div>
                    <div class="stat-icon">⚽</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card bg-green d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-bold">TỔNG KHÁCH HÀNG</h5>
                        <h2><?php echo $count_user; ?></h2>
                    </div>
                    <div class="stat-icon">👥</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card bg-red d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-bold">TỔNG DOANH THU</h5>
                        <h2><?php echo number_format($sum_revenue, 0, ',', '.'); ?>đ</h2>
                    </div>
                    <div class="stat-icon">💰</div>
                </div>
            </div>
        </div>

        <div class="admin-table-container">
            <ul class="nav nav-tabs admin-tabs" id="adminTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="match-tab" data-toggle="tab" href="#match" role="tab">QUẢN LÝ TRẬN ĐẤU</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="user-tab" data-toggle="tab" href="#user" role="tab">QUẢN LÝ KHÁCH HÀNG</a>
                </li>
            </ul>

            <div class="tab-content" id="adminTabContent">
                
                <div class="tab-pane fade show active" id="match" role="tabpanel">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="font-weight-bold text-secondary">Danh sách trận đấu</h5>
                        <a href="#" class="btn btn-success font-weight-bold">+ Thêm Trận Đấu</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-admin border">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Giải Đấu</th>
                                    <th>Trận Đấu</th>
                                    <th>Thời Gian</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-center">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($matches as $m): ?>
                                <tr>
                                    <td><b>#<?php echo $m['id']; ?></b></td>
                                    <td><?php echo htmlspecialchars($m['ten_giai']); ?></td>
                                    <td class="font-weight-bold text-primary">
                                        <?php echo htmlspecialchars($m['ten_nha']); ?> - <?php echo htmlspecialchars($m['ten_khach']); ?>
                                    </td>
                                    <td><?php echo date('H:i d/m/Y', strtotime($m['thoi_gian'])); ?></td>
                                    <td>
                                        <?php 
                                            if($m['trang_thai'] == 'sap_dien_ra') echo "<span class='badge badge-success'>Sắp diễn ra</span>";
                                            elseif($m['trang_thai'] == 'dang_da') echo "<span class='badge badge-warning'>Đang đá</span>";
                                            else echo "<span class='badge badge-secondary'>Đã kết thúc</span>";
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="text-info font-weight-bold mr-2">✏️ Sửa</a>
                                        <a href="index.php?action=delete_match&id=<?php echo $m['id']; ?>" class="text-danger font-weight-bold" onclick="return confirm('Bạn có chắc chắn muốn xóa trận đấu này không?');">🗑️ Xóa</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="user" role="tabpanel">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="font-weight-bold text-secondary">Danh sách khách hàng</h5>
                        <a href="#" class="btn btn-primary font-weight-bold">+ Thêm Khách Hàng</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-admin border">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Họ & Tên</th>
                                    <th>Tên Đăng Nhập</th>
                                    <th>Email / SĐT</th>
                                    <th>Số Dư</th>
                                    <th class="text-center">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $u): ?>
                                <tr>
                                    <td><b>#<?php echo $u['id']; ?></b></td>
                                    <td class="font-weight-bold"><?php echo htmlspecialchars($u['ho_ten']); ?></td>
                                    <td><?php echo htmlspecialchars($u['ten_dang_nhap']); ?></td>
                                    <td>
                                        <div>✉️ <?php echo htmlspecialchars($u['email']); ?></div>
                                        <div>📞 <?php echo htmlspecialchars($u['sdt']); ?></div>
                                    </td>
                                    <td class="font-weight-bold text-danger"><?php echo number_format($u['so_du'], 0, ',', '.'); ?>đ</td>
                                    <td class="text-center">
                                        <a href="#" class="text-info font-weight-bold mr-2">✏️ Sửa</a>
                                        <a href="index.php?action=delete_user&id=<?php echo $u['id']; ?>" class="text-danger font-weight-bold" onclick="return confirm('Xác nhận xóa khách hàng này? Mọi dữ liệu liên quan có thể bị mất!');">🗑️ Xóa</a>
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