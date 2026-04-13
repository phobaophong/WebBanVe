<?php
session_start();
require_once 'config/database.php';
include 'includes/home_logic.php';
include 'includes/header.php';
include 'includes/navbar.php';
?>
<!-- banner -->
<div class="container mt-4">
    <?php
    $banner_dir = "assets/images/banners/";
    $banners = glob($banner_dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    $total_banners = count($banners);
    if ($total_banners > 0):
        ?>
        <div id="leagueBanner" class="carousel slide shadow-sm league-banner-container" data-ride="carousel"
            data-interval="5000">
            <ol class="carousel-indicators">
                <?php for ($i = 0; $i < $total_banners; $i++): ?>
                    <li data-target="#leagueBanner" data-slide-to="<?php echo $i; ?>"
                        class="<?php echo ($i == 0) ? 'active' : ''; ?>"></li>
                <?php endfor; ?>
            </ol>
            <div class="carousel-inner">
                <?php foreach ($banners as $index => $banner_path): ?>
                    <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                        <img src="<?php echo $banner_path; ?>" class="d-block w-100 banner-img"
                            alt="Banner <?php echo $index + 1; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($total_banners > 1): ?>
                <button class="carousel-control-prev carousel-control-btn" type="button" data-target="#leagueBanner"
                    data-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next carousel-control-btn" type="button" data-target="#leagueBanner"
                    data-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<!-- lọc nhanh -->
<div class="container mt-3 mb-2">
    <ul class="league-nav-horizontal">
        <li class="<?php echo ($active_menu == 'upcoming') ? 'active' : ''; ?>">
            <a href="index.php"><img src="assets/images/logos_giaidau/logo5.png" class="league-icon">Các trận sắp
                tới</a>
        </li>
        <li class="<?php echo ($active_menu == 'league_1') ? 'active' : ''; ?>">
            <a href="index.php?league_id=1"><img src="assets/images/logos_giaidau/logo1.png" alt="EPL"
                    class="league-icon"> Ngoại hạng Anh</a>
        </li>
        <li class="<?php echo ($active_menu == 'league_2') ? 'active' : ''; ?>">
            <a href="index.php?league_id=2"><img src="assets/images/logos_giaidau/logo2.png" alt="Serie A"
                    class="league-icon"> Serie A</a>
        </li>
        <li class="<?php echo ($active_menu == 'league_3') ? 'active' : ''; ?>">
            <a href="index.php?league_id=3"><img src="assets/images/logos_giaidau/logo3.png" alt="Bundesliga"
                    class="league-icon"> Bundesliga</a>
        </li>
        <li class="<?php echo ($active_menu == 'league_4') ? 'active' : ''; ?>">
            <a href="index.php?league_id=4"><img src="assets/images/logos_giaidau/logo4.png" alt="La Liga"
                    class="league-icon"> Laliga</a>
        </li>
    </ul>
</div>

<div class="container mt-2">
    <?php if (isset($_SESSION['success_msg'])): ?>
        <div class="alert alert-success alert-custom-success alert-homepage text-center font-weight-bold">
            <?php echo $_SESSION['success_msg'];
            unset($_SESSION['success_msg']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-homepage text-center font-weight-bold">
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
</div>

<div class="container">
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <p class="subtitle mb-4">Nhanh tay đặt vé để không bỏ lỡ những trận cầu đỉnh cao!</p>
    <div class="row">

        <!--  bên trái  -->
        <aside class="col-12 col-lg-3 mb-4 mb-lg-0">
            <div class="sidebar-box">
                <h4 class="sidebar-heading">
                    <img src="assets/images/system/icon-filter.png" class="sys-icon" alt="icon"> LỌC NÂNG CAO
                    <form action="index.php" method="GET">
                        <?php if (isset($_GET['league_id'])): ?>
                            <input type="hidden" name="league_id"
                                value="<?php echo htmlspecialchars($_GET['league_id']); ?>">
                        <?php endif; ?>
                        <div class="form-group mb-4">
                            <label class="filter-label">Ngày thi đấu</label>
                            <div class="d-flex flex-column gap-2">
                                <input type="date" name="from_date" class="form-control date-input"
                                    value="<?php echo isset($_GET['from_date']) ? htmlspecialchars($_GET['from_date']) : ''; ?>">
                                <div class="text-center text-muted font-weight-bold">↓</div>
                                <input type="date" name="to_date" class="form-control date-input"
                                    value="<?php echo isset($_GET['to_date']) ? htmlspecialchars($_GET['to_date']) : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label class="filter-label">Đội bóng</label>
                            <div class="team-list-scroll">
                                <?php foreach ($teams_list as $team): ?>
                                    <?php $is_checked = in_array($team['id'], $selected_teams) ? 'checked' : ''; ?>
                                    <div class="custom-control custom-checkbox mb-2 team-checkbox-item">
                                        <div class="team-label">
                                            <input type="checkbox" class="custom-control-input"
                                                id="team_<?php echo $team['id']; ?>" name="teams[]"
                                                value="<?php echo $team['id']; ?>" <?php echo $is_checked; ?>>
                                            <label class="custom-control-label d-flex align-items-center cursor-pointer"
                                                for="team_<?php echo $team['id']; ?>">
                                                <img src="assets/images/logos/<?php echo $team['id']; ?>.png"
                                                    onerror="this.src='assets/images/logos/default.png'"
                                                    class="team-filter-logo">
                                                <?php echo htmlspecialchars($team['ten_doi']); ?>
                                            </label>
                                        </div>
                                        <span class="team-count">(<?php echo $team['so_tran']; ?>)</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-block btn-filter">ÁP DỤNG LỌC</button>
                        <?php if (!empty($_GET['from_date']) || !empty($_GET['to_date']) || !empty($selected_teams)): ?>
                            <a href="index.php<?php echo isset($_GET['league_id']) ? '?league_id=' . $_GET['league_id'] : ''; ?>"
                                class="btn btn-outline-secondary btn-block mt-2">Xóa bộ lọc</a>
                        <?php endif; ?>
                    </form>
            </div>
        </aside>
        <!--chính  giữa-->
        <main class="col-12 col-lg-6">
            <div class="match-grid">
                <?php
                $sql = "SELECT  t.id, 
                                t.thoi_gian, 
                                t.san_van_dong, 
                                t.id_doi_nha, 
                                t.id_doi_khach,
                                g.ten_giai, 
                                dn.ten_doi AS ten_doi_nha, 
                                dk.ten_doi AS ten_doi_khach
                        FROM tbl_trandau t
                        JOIN tbl_giaidau g ON t.id_giaidau = g.id
                        JOIN tbl_doibong dn ON t.id_doi_nha = dn.id
                        JOIN tbl_doibong dk ON t.id_doi_khach = dk.id
                        WHERE $where_clause ORDER BY t.thoi_gian ASC";

                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
                $all_matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $total_matches = count($all_matches);

                $is_view_all = isset($_GET['view']) && $_GET['view'] == 'all';
                $display_limit = $is_view_all ? $total_matches : 6;
                $show_btn = (!$is_view_all && $total_matches > 6);

                if ($total_matches > 0) {
                    $count = 0;
                    foreach ($all_matches as $row) {
                        if ($count >= $display_limit)
                            break;
                        $thoi_gian_format = date('H:i - d/m/Y', strtotime($row['thoi_gian']));
                        $logo_nha = "assets/images/logos/" . $row['id_doi_nha'] . ".png";
                        $logo_khach = "assets/images/logos/" . $row['id_doi_khach'] . ".png";
                        echo "<div class='match-card'>";
                        echo "<span class='league-name'>" . htmlspecialchars($row['ten_giai']) . "</span>";
                        echo "<div class='team-matchup'>";
                        echo "  <div class='team'><img src='$logo_nha' onerror=\"this.src='assets/images/logos/default.png'\" alt='Nhà' class='team-logo' width='60' height='60'><p class='team-name'>" . htmlspecialchars($row['ten_doi_nha']) . "</p></div>";
                        echo "  <div class='vs-box'>VS</div>";
                        echo "  <div class='team'><img src='$logo_khach' onerror=\"this.src='assets/images/logos/default.png'\" alt='Khách' class='team-logo' width='60' height='60'><p class='team-name'>" . htmlspecialchars($row['ten_doi_khach']) . "</p></div>";
                        echo "</div>";
                        echo "<p class='match-time'><b><img src='assets/images/system/icon-time.png' class='sys-icon'> Thời gian:</b> $thoi_gian_format</p>";
                        echo "<p class='match-stadium'><b><img src='assets/images/system/icon-stadium.png' class='sys-icon'> Sân:</b> " . htmlspecialchars($row['san_van_dong']) . "</p>";
                        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                            echo "<a href='pages/checkout.php?id_trandau=" . $row['id'] . "' class='btn btn-success btn-block font-weight-bold'><img src='assets/images/system/icon-ticket.png' class='sys-icon-btn'> MUA VÉ NGAY</a>";
                        } else {
                            echo "<span class='btn btn-secondary btn-block disabled admin-view-disabled'><img src='assets/images/system/icon-eye.png' class='sys-icon-btn'> Chế độ xem Admin</span>";
                        }
                        echo "</div>";
                        $count++;
                    }
                } else {
                    echo "<div class='text-center empty-state-box w-100'><h5 class='empty-state-title'>Không tìm thấy trận đấu nào!</h5><p>Vui lòng thay đổi ngày hoặc chọn đội bóng khác.</p></div>";
                }
                ?>
            </div>
            <?php
            if ($show_btn) {
                $get_params = $_GET;
                $get_params['view'] = 'all';
                $view_all_url = 'index.php?' . http_build_query($get_params);
                echo "<div class='text-center mt-4 mb-4'>";
                echo "  <a href='" . htmlspecialchars($view_all_url) . "' class='btn btn-warning font-weight-bold btn-more-custom'>Xem thêm</a>";
                echo "</div>";
            }
            ?>
        </main>
        <!-- bên phải -->
        <aside class="col-12 col-lg-3">
            <div class="widget-box widget-box-custom">
                <h3 class="p-3 mb-0 text-center widget-header-blue">
                    <img src="assets/images/system/icon-tv.png" class="sys-icon" alt="icon"> HIGHLIGHTS MỚI NHẤT
                </h3>
                <iframe src="https://www.scorebat.com/embed/" frameborder="0" allowfullscreen
                    allow='autoplay; fullscreen' class="iframe-highlight"></iframe>
            </div>

            <div class="widget-box mt-3 widget-box-custom">
                <h3 class="p-3 mb-0 text-center widget-header-green">
                    <img src="assets/images/system/icon-fire.png" class="sys-icon" alt="icon"> BẢNG XẾP HẠNG EPL
                </h3>

                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 table-ranking">
                        <thead class="ranking-thead">
                            <tr>
                                <th class="text-center col-rank">#</th>
                                <th class="col-club">Câu lạc bộ</th>
                                <th class="text-center col-match">Trận</th>
                                <th class="text-center col-pts">Điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="ranking-top1">
                                <td class="text-center font-weight-bold">1</td>
                                <td class="font-weight-bold">
                                    <img src="assets/images/logos/1.png"
                                        onerror="this.src='assets/images/logos/default.png'" class="mr-1 ranking-logo"
                                        alt="Arsenal">
                                    Arsenal
                                </td>
                                <td class="text-center">32</td>
                                <td class="text-center font-weight-bold text-success">71</td>
                            </tr>
                            <tr>
                                <td class="text-center font-weight-bold">2</td>
                                <td class="font-weight-bold">
                                    <img src="assets/images/logos/12.png"
                                        onerror="this.src='assets/images/logos/default.png'" class="mr-1 ranking-logo"
                                        alt="Liverpool">
                                    Liverpool
                                </td>
                                <td class="text-center">32</td>
                                <td class="text-center font-weight-bold">71</td>
                            </tr>
                            <tr>
                                <td class="text-center font-weight-bold">3</td>
                                <td class="font-weight-bold">
                                    <img src="assets/images/logos/13.png"
                                        onerror="this.src='assets/images/logos/default.png'" class="mr-1 ranking-logo"
                                        alt="Man City">
                                    Man City
                                </td>
                                <td class="text-center">32</td>
                                <td class="text-center font-weight-bold">70</td>
                            </tr>
                            <tr>
                                <td class="text-center font-weight-bold">4</td>
                                <td class="font-weight-bold">
                                    <img src="assets/images/logos/2.png"
                                        onerror="this.src='assets/images/logos/default.png'" class="mr-1 ranking-logo"
                                        alt="Aston Villa">
                                    Aston Villa
                                </td>
                                <td class="text-center">33</td>
                                <td class="text-center font-weight-bold">63</td>
                            </tr>
                            <tr>
                                <td class="text-center font-weight-bold">5</td>
                                <td class="font-weight-bold">
                                    <img src="assets/images/logos/18.png"
                                        onerror="this.src='assets/images/logos/default.png'" class="mr-1 ranking-logo"
                                        alt="Tottenham">
                                    Tottenham
                                </td>
                                <td class="text-center">32</td>
                                <td class="text-center font-weight-bold">60</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-center p-2 ranking-footer">
                    <a href="#" class="ranking-link">Xem toàn bộ bảng xếp hạng &rarr;</a>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php include 'includes/footer.php'; ?>