<?php
session_start();
require_once 'config/database.php'; 
include 'includes/header.php';
include 'includes/navbar.php';

// ==========================================================================
// 1. LẤY DANH SÁCH ĐỘI BÓNG ĐỂ ĐƯA VÀO BỘ LỌC
// ==========================================================================
$sql_teams = "SELECT d.id, d.ten_doi, 
                   (SELECT COUNT(*) FROM tbl_trandau t 
                    WHERE t.trang_thai = 'sap_dien_ra' 
                    AND (t.id_doi_nha = d.id OR t.id_doi_khach = d.id)) AS so_tran
              FROM tbl_doibong d 
              HAVING so_tran > 0 
              ORDER BY d.ten_doi ASC";
$stmt_teams = $conn->query($sql_teams);
$teams_list = $stmt_teams->fetchAll(PDO::FETCH_ASSOC);

// ==========================================================================
// 2. XỬ LÝ LOGIC LỌC DỮ LIỆU
// ==========================================================================
$where_clause = "t.trang_thai = 'sap_dien_ra'";
$params = [];

// A. Lọc theo Menu Giải đấu nằm ngang
if (isset($_GET['league_id'])) {
    $where_clause .= " AND t.id_giaidau = :league_id";
    $params['league_id'] = $_GET['league_id'];
    $active_menu = 'league_' . $_GET['league_id'];
} else {
    $active_menu = 'upcoming';
}

// B. Lọc theo Form Ngày tháng
if (!empty($_GET['from_date'])) {
    $where_clause .= " AND DATE(t.thoi_gian) >= :from_date";
    $params[':from_date'] = $_GET['from_date'];
}
if (!empty($_GET['to_date'])) {
    $where_clause .= " AND DATE(t.thoi_gian) <= :to_date";
    $params[':to_date'] = $_GET['to_date'];
}

// C. Lọc theo Form Đội bóng (Checkbox)
$selected_teams = isset($_GET['teams']) && is_array($_GET['teams']) ? $_GET['teams'] : [];
if (!empty($selected_teams)) {
    $team_placeholders = [];
    foreach ($selected_teams as $index => $team_id) {
        $param_name = ":team_" . $index;
        $team_placeholders[] = $param_name;
        $params[$param_name] = $team_id;
    }
    $in_clause = implode(',', $team_placeholders);
    $where_clause .= " AND (t.id_doi_nha IN ($in_clause) OR t.id_doi_khach IN ($in_clause))";
}

// Thiết lập Tiêu đề trang
if (empty($_GET['from_date']) && empty($_GET['to_date']) && empty($selected_teams) && empty($_GET['league_id'])) {
    $where_clause .= " AND DATE(t.thoi_gian) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
    $page_title = "CÁC TRẬN ĐẤU TRONG TUẦN TỚI";
} else {
    $page_title = "KẾT QUẢ TÌM KIẾM";
}
?>

<div class="container mt-4">
    <div id="leagueBanner" class="carousel slide shadow-sm league-banner-container" data-ride="carousel" data-interval="5000">
        <ol class="carousel-indicators">
            <li data-target="#leagueBanner" data-slide-to="0" class="active"></li>
            <li data-target="#leagueBanner" data-slide-to="1"></li>
            <li data-target="#leagueBanner" data-slide-to="2"></li>
            <li data-target="#leagueBanner" data-slide-to="3"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active"><img src="assets/images/banners/banner1.png" class="d-block w-100 banner-img" alt="Banner 1"></div>
            <div class="carousel-item"><img src="assets/images/banners/banner2.png" class="d-block w-100 banner-img" alt="Banner 2"></div>
            <div class="carousel-item"><img src="assets/images/banners/banner3.png" class="d-block w-100 banner-img" alt="Banner 3"></div>
            <div class="carousel-item"><img src="assets/images/banners/banner4.png" class="d-block w-100 banner-img" alt="Banner 4"></div>
        </div>
        <button class="carousel-control-prev carousel-control-btn" type="button" data-target="#leagueBanner" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next carousel-control-btn" type="button" data-target="#leagueBanner" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>

<div class="container mt-3 mb-2">
    <ul class="league-nav-horizontal">
        <li class="<?php echo ($active_menu == 'upcoming') ? 'active' : ''; ?>">
            <a href="index.php"><span class="league-icon" style="line-height: 1;">🌍</span> Các trận sắp tới</a>
        </li>
        <li class="<?php echo ($active_menu == 'league_1') ? 'active' : ''; ?>">
            <a href="index.php?league_id=1"><img src="assets/images/logos_giaidau/logo1.png" alt="EPL" class="league-icon"> Ngoại hạng Anh</a>
        </li>
        <li class="<?php echo ($active_menu == 'league_2') ? 'active' : ''; ?>">
            <a href="index.php?league_id=2"><img src="assets/images/logos_giaidau/logo2.png" alt="Serie A" class="league-icon"> Serie A</a>
        </li>
        <li class="<?php echo ($active_menu == 'league_3') ? 'active' : ''; ?>">
            <a href="index.php?league_id=3"><img src="assets/images/logos_giaidau/logo3.png" alt="Bundesliga" class="league-icon"> Bundesliga</a>
        </li>
        <li class="<?php echo ($active_menu == 'league_4') ? 'active' : ''; ?>">
            <a href="index.php?league_id=4"><img src="assets/images/logos_giaidau/logo4.png" alt="La Liga" class="league-icon"> Laliga</a>
        </li>
    </ul>
</div>

<div class="container">
    <div class="row mt-2">
        
        <aside class="col-12 col-lg-3 mb-4 mb-lg-0">
            <div class="sidebar-box">
                <h4 class="sidebar-heading"><span class="sidebar-icon">⧉</span> LỌC NÂNG CAO</h4>
                
                <form action="index.php" method="GET">
                    <?php if(isset($_GET['league_id'])): ?>
                        <input type="hidden" name="league_id" value="<?php echo htmlspecialchars($_GET['league_id']); ?>">
                    <?php endif; ?>

                    <div class="form-group mb-4">
                        <label class="filter-label">Ngày thi đấu</label>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <input type="date" name="from_date" class="form-control date-input" value="<?php echo isset($_GET['from_date']) ? htmlspecialchars($_GET['from_date']) : ''; ?>">
                            <div style="text-align: center; color: #999; font-weight: bold; line-height: 1;">↓</div>
                            <input type="date" name="to_date" class="form-control date-input" value="<?php echo isset($_GET['to_date']) ? htmlspecialchars($_GET['to_date']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="filter-label">Đội bóng</label>
                        <div class="team-list-scroll">
                            <?php foreach($teams_list as $team): ?>
                                <?php $is_checked = in_array($team['id'], $selected_teams) ? 'checked' : ''; ?>
                                <div class="custom-control custom-checkbox mb-2 team-checkbox-item">
                                    <div class="team-label">
                                        <input type="checkbox" class="custom-control-input" id="team_<?php echo $team['id']; ?>" name="teams[]" value="<?php echo $team['id']; ?>" <?php echo $is_checked; ?>>
                                        <label class="custom-control-label d-flex align-items-center" for="team_<?php echo $team['id']; ?>" style="cursor: pointer;">
                                            <img src="assets/images/logos/<?php echo $team['id']; ?>.png" onerror="this.src='assets/images/logos/default.png'" class="team-filter-logo">
                                            <?php echo htmlspecialchars($team['ten_doi']); ?>
                                        </label>
                                    </div>
                                    <span class="team-count">(<?php echo $team['so_tran']; ?>)</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-block btn-filter">ÁP DỤNG LỌC</button>
                    
                    <?php if(!empty($_GET['from_date']) || !empty($_GET['to_date']) || !empty($selected_teams)): ?>
                        <a href="index.php<?php echo isset($_GET['league_id']) ? '?league_id='.$_GET['league_id'] : ''; ?>" class="btn btn-outline-secondary btn-block mt-2">Xóa bộ lọc</a>
                    <?php endif; ?>
                </form>
            </div>
        </aside>

        <main class="col-12 col-lg-6">
            <h1 class="page-title"><?php echo $page_title; ?></h1>
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
                
                $all_matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $total_matches = count($all_matches);
                
                // Xác định số lượng hiển thị (Tối đa 4 trận nếu chưa bấm Xem tất cả)
                $is_view_all = isset($_GET['view']) && $_GET['view'] == 'all';
                $display_limit = $is_view_all ? $total_matches : 4; 
                $show_btn = (!$is_view_all && $total_matches > 4);

                if ($total_matches > 0) {
                    $count = 0;
                    foreach($all_matches as $row) {
                        if ($count >= $display_limit) break;

                        $thoi_gian_format = date('H:i - d/m/Y', strtotime($row['thoi_gian']));
                        $path_nha = "assets/images/logos/" . $row['id_doi_nha'] . ".png";
                        $path_khach = "assets/images/logos/" . $row['id_doi_khach'] . ".png";

                        $logo_nha = file_exists($path_nha) ? $path_nha : "assets/images/logos/default.png";
                        $logo_khach = file_exists($path_khach) ? $path_khach : "assets/images/logos/default.png";

                        echo "<div class='match-card'>";
                        echo "<span class='league-name'>" . htmlspecialchars($row['ten_giai']) . "</span>";
                        
                        echo "<div class='team-matchup'>";
                        echo "  <div class='team'><img src='$logo_nha' alt='Nhà' class='team-logo' width='60' height='60'><p class='team-name'>" . htmlspecialchars($row['ten_doi_nha']) . "</p></div>";
                        echo "  <div class='vs-box'>VS</div>";
                        echo "  <div class='team'><img src='$logo_khach' alt='Khách' class='team-logo' width='60' height='60'><p class='team-name'>" . htmlspecialchars($row['ten_doi_khach']) . "</p></div>";
                        echo "</div>";

                        echo "<p class='match-time'><b>⏰ Thời gian:</b> $thoi_gian_format</p>";
                        echo "<p class='match-stadium'><b>🏟️ Sân:</b> " . htmlspecialchars($row['san_van_dong']) . "</p>";
                        
                        echo "<a href='pages/checkout.php?id_trandau=" . $row['id'] . "' class='btn btn-primary btn-block'>MUA VÉ NGAY</a>";
                        echo "</div>";
                        
                        $count++;
                    }
                } else {
                    echo "<div class='text-center empty-state-box w-100'>
                            <h5 class='empty-state-title'>Không tìm thấy trận đấu nào!</h5>
                            <p>Vui lòng thay đổi ngày hoặc chọn đội bóng khác.</p>
                          </div>";
                }
                ?>
            </div>

            <?php
            // Hiển thị nút "Xem thêm" nếu còn trận đấu bị ẩn
            if ($show_btn) {
                // Lấy lại các param cũ (như lọc ngày, đội bóng) và gán thêm view=all
                $get_params = $_GET;
                $get_params['view'] = 'all';
                $view_all_url = 'index.php?' . http_build_query($get_params);
                $matches_left = $total_matches - 4;

                echo "<div class='text-center mt-4 mb-4'>";
                echo "  <a href='" . htmlspecialchars($view_all_url) . "' class='btn btn-warning' style='padding: 10px 30px; border-radius: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>";
                echo "      Hiển thị thêm " . $matches_left . " trận đấu nữa ⬇️";
                echo "  </a>";
                echo "</div>";
            }
            ?>
        </main>

        <aside class="col-12 col-lg-3">
            <div class="widget-box">
                <h3>📰 Bảng Tin Thể Thao</h3>
                <p class="footer-subtext">Đang cập nhật.</p>
            </div>
            <div class="widget-box mt-3">
                <h3>🔥 Bảng Xếp Hạng</h3>
                <p class="footer-subtext">Đang cập nhật.</p>
            </div>
        </aside>

    </div>
</div>

<?php include 'includes/footer.php'; ?>