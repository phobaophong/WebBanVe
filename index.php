<?php
session_start();
require_once 'config/database.php'; 
include 'includes/header.php';
include 'includes/navbar.php';

// ==========================================================================
// XỬ LÝ LOGIC LỌC TRẬN ĐẤU (FILTER)
// ==========================================================================
$where_clause = "t.trang_thai = 'sap_dien_ra'";
$params = [];
$active_menu = 'upcoming'; 
$show_view_all_btn = false; // Biến điều khiển việc hiển thị nút "Xem tất cả"

if (isset($_GET['league_id'])) {
    $where_clause .= " AND t.id_giaidau = :league_id";
    $params['league_id'] = $_GET['league_id'];
    $active_menu = 'league_' . $_GET['league_id'];
    
    // Kiểm tra xem khách đang ở chế độ xem 7 ngày hay chế độ "Xem tất cả"
    if (!isset($_GET['view']) || $_GET['view'] !== 'all') {
        // NẾU CHƯA BẤM NÚT: Chỉ lọc 7 ngày tới và bật cờ hiển thị nút
        $where_clause .= " AND DATE(t.thoi_gian) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
        $show_view_all_btn = true;
    }
} elseif (isset($_GET['filter']) && $_GET['filter'] == 'all') {
    $active_menu = 'all';
} else {
    // MẶC ĐỊNH TRANG CHỦ: Lọc 7 ngày tới (không hiện nút vì đã có menu bên trái lo)
    $where_clause .= " AND DATE(t.thoi_gian) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
}
?>

<div class="container home-layout">
    
    <aside class="sidebar-left">
        <ul class="league-menu">
            <li class="<?php echo ($active_menu == 'upcoming') ? 'active' : ''; ?>">
                <a href="index.php">🌍 Các trận đấu sắp tới</a>
            </li>
            <li class="<?php echo ($active_menu == 'league_1') ? 'active' : ''; ?>">
                <a href="index.php?league_id=1" class="border-purple">🦁 Ngoại hạng Anh</a>
            </li>
            <li><a href="#" class="border-blue">🔵 Serie A</a></li>
            <li><a href="#" class="border-red">🦅 Bundesliga</a></li>
            <li><a href="#" class="border-orange">🔴 Laliga</a></li>
        </ul>
    </aside>

    <main class="main-center">
        <?php
        // Đổi tiêu đề động để khách hàng biết họ đang xem gì
        if ($active_menu == 'upcoming') {
            echo "<h1 class='page-title'>CÁC TRẬN ĐẤU TRONG TUẦN TỚI</h1>";
        } elseif (isset($_GET['league_id'])) {
            if (isset($_GET['view']) && $_GET['view'] == 'all') {
                echo "<h1 class='page-title'>TẤT CẢ TRẬN ĐẤU CỦA GIẢI</h1>";
            } else {
                echo "<h1 class='page-title'>LỊCH THI ĐẤU GIẢI (7 NGÀY TỚI)</h1>";
            }
        }
        ?>
        <p class="subtitle">Nhanh tay đặt vé để không bỏ lỡ những trận cầu đỉnh cao!</p>

        <div class="match-grid">
            <?php
            $sql = "SELECT t.id, t.thoi_gian, t.san_van_dong, t.id_doi_nha, t.id_doi_khach,
                           g.ten_giai, 
                           dn.ten_doi AS ten_doi_nha, 
                           dk.ten_doi AS ten_doi_khach
                    FROM tbl_trandau t
                    JOIN tbl_giaidau g ON t.id_giaidau = g.id
                    JOIN tbl_doibong dn ON t.id_doi_nha = dn.id
                    JOIN tbl_doibong dk ON t.id_doi_khach = dk.id
                    WHERE $where_clause
                    ORDER BY t.thoi_gian ASC";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            if ($stmt->rowCount() > 0) {
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $thoi_gian_format = date('H:i - d/m/Y', strtotime($row['thoi_gian']));
                    
                    $path_nha = "assets/images/logos/" . $row['id_doi_nha'] . ".png";
                    $path_khach = "assets/images/logos/" . $row['id_doi_khach'] . ".png";

                    $logo_nha = file_exists($path_nha) ? $path_nha : "assets/images/logos/default.png";
                    $logo_khach = file_exists($path_khach) ? $path_khach : "assets/images/logos/default.png";

                    echo "<div class='match-card'>";
                    echo "<span class='league-name'>" . htmlspecialchars($row['ten_giai']) . "</span>";
                    
                    echo "<div class='team-matchup'>";
                    echo "  <div class='team'><img src='$logo_nha' alt='Nhà' class='team-logo'><p>" . htmlspecialchars($row['ten_doi_nha']) . "</p></div>";
                    echo "  <div class='vs-box'><b>VS</b></div>";
                    echo "  <div class='team'><img src='$logo_khach' alt='Khách' class='team-logo'><p>" . htmlspecialchars($row['ten_doi_khach']) . "</p></div>";
                    echo "</div>";

                    echo "<p><b>Thời gian:</b> $thoi_gian_format</p>";
                    echo "<p><b>Sân:</b> " . htmlspecialchars($row['san_van_dong']) . "</p>";
                    
                    echo "<a href='pages/checkout.php?id_trandau=" . $row['id'] . "' class='btn btn-primary btn-block'>MUA VÉ NGAY</a>";
                    echo "</div>";
                }
            } else {
                echo "<div class='widget-box text-center' style='grid-column: 1 / -1;'>Hiện chưa có trận đấu nào trong thời gian này.</div>";
            }
            ?>
        </div>

        <?php
        // NÚT XEM TẤT CẢ NẰM ĐỘC LẬP BÊN DƯỚI LƯỚI TRẬN ĐẤU
        if ($show_view_all_btn) {
            echo "<div style='text-align: center; margin-top: 30px; margin-bottom: 20px;'>";
            echo "  <a href='index.php?league_id=" . $_GET['league_id'] . "&view=all' class='btn btn-warning' style='padding: 12px 30px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-size: 16px;'>Xem tất cả lịch thi đấu của giải này ⬇️</a>";
            echo "</div>";
        }
        ?>

    </main>

    <aside class="sidebar-right">
        <div class="widget-box" style="margin-bottom: 20px;">
            <h3>📰 Bảng Tin Thể Thao</h3>
            <p class="footer-subtext">Đang cập nhật.</p>
        </div>
        <div class="widget-box">
            <h3>🔥 Bảng Xếp Hạng</h3>
            <p class="footer-subtext">Đang cập nhật.</p>
        </div>
    </aside>

</div>

<?php include 'includes/footer.php'; ?>