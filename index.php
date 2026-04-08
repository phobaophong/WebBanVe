<?php
session_start();
require_once 'config/database.php'; 
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container home-layout">
    
    <aside class="sidebar-left">
        <ul class="league-menu">
            <li class="active"><a href="#">⚽ Lịch thi đấu hôm nay</a></li>
            <li><a href="#" class="border-red">🏆 FA Cup</a></li>
            <li><a href="#" class="border-purple">🦁 Ngoại hạng Anh</a></li>
            <li><a href="#" class="border-black">⭐ CUP C1</a></li>
            <li><a href="#" class="border-orange">🔴 La Liga</a></li>
            <li><a href="#" class="border-red">🇻🇳 V.League 1</a></li>
            <li><a href="#" class="border-blue">🔵 Serie A</a></li>
            <li><a href="#" class="border-orange">🟠 UEFA Europa League</a></li>
            <li><a href="#" class="border-red">🦅 Bundesliga</a></li>
            <li><a href="#" class="border-navy">🔷 Ligue 1</a></li>
        </ul>
    </aside>

    <main class="main-center">
        <h1 class="page-title" style="margin-top: 0;">TRẬN ĐẤU SẮP DIỄN RA</h1>
        <p class="subtitle">Nhanh tay đặt vé để không bỏ lỡ những trận cầu đỉnh cao!</p>

        <div class="match-grid">
            <?php
            // Lấy thêm id_doi_nha và id_doi_khach để truy xuất file logo
            $sql = "SELECT t.id, t.thoi_gian, t.san_van_dong, t.id_doi_nha, t.id_doi_khach,
                           g.ten_giai, 
                           dn.ten_doi AS ten_doi_nha, 
                           dk.ten_doi AS ten_doi_khach
                    FROM tbl_trandau t
                    JOIN tbl_giaidau g ON t.id_giaidau = g.id
                    JOIN tbl_doibong dn ON t.id_doi_nha = dn.id
                    JOIN tbl_doibong dk ON t.id_doi_khach = dk.id
                    WHERE t.trang_thai = 'sap_dien_ra'
                    ORDER BY t.thoi_gian ASC LIMIT 6";
            
            $result = $conn->query($sql);

            if ($result->rowCount() > 0) {
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $thoi_gian_format = date('H:i - d/m/Y', strtotime($row['thoi_gian']));
                    
                    // --- XỬ LÝ LOGO TỰ ĐỘNG THEO ID ĐỘI BÓNG ---
                    $path_nha = "assets/images/logos/" . $row['id_doi_nha'] . ".png";
                    $path_khach = "assets/images/logos/" . $row['id_doi_khach'] . ".png";

                    $logo_nha = file_exists($path_nha) ? $path_nha : "assets/images/logos/default.png";
                    $logo_khach = file_exists($path_khach) ? $path_khach : "assets/images/logos/default.png";
                    // -------------------------------------------

                    echo "<div class='match-card'>";
                    echo "<span class='league-name'>" . htmlspecialchars($row['ten_giai']) . "</span>";
                    
                    // Khối hiển thị Logo 2 bên và VS ở giữa
                    echo "<div class='team-matchup'>";
                    echo "  <div class='team'><img src='$logo_nha' alt='Nhà' class='team-logo'><p>" . htmlspecialchars($row['ten_doi_nha']) . "</p></div>";
                    echo "  <div class='vs-box'><b>VS</b></div>";
                    echo "  <div class='team'><img src='$logo_khach' alt='Khách' class='team-logo'><p>" . htmlspecialchars($row['ten_doi_khach']) . "</p></div>";
                    echo "</div>";

                    echo "<p style='margin-top:10px;'><b>Thời gian:</b> $thoi_gian_format</p>";
                    echo "<p><b>Sân:</b> " . htmlspecialchars($row['san_van_dong']) . "</p>";
                    echo "<a href='pages/checkout.php?id_trandau=" . $row['id'] . "' class='btn btn-primary' style='margin-top:10px;'>MUA VÉ NGAY</a>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-center'>Hiện chưa có trận đấu nào sắp diễn ra.</p>";
            }
            ?>
        </div>
    </main>

    <aside class="sidebar-right">
        <div class="widget-box">
            <h3>📰 Bảng Tin Thể Thao</h3>
            <p style="color: #666; font-size: 13px;">Đang cập nhật.</p>
        </div>
        <div class="widget-box" style="margin-top: 20px;">
            <h3>🔥 Bảng Xếp Hạng</h3>
            <p style="color: #666; font-size: 13px;">Đang cập nhật.</p>
        </div>
    </aside>

</div>

<?php include 'includes/footer.php'; ?>