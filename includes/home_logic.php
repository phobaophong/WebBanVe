<?php
// Cập nhật trạng thái trận đấu dựa trên thời gian hiện tại
try {
    $conn->exec("
        UPDATE tbl_trandau 
        SET trang_thai = 'dang_da' 
        WHERE trang_thai = 'sap_dien_ra' 
        AND thoi_gian <= NOW() 
        AND NOW() < DATE_ADD(thoi_gian, INTERVAL 120 MINUTE)
    ");

    $conn->exec("
        UPDATE tbl_trandau 
        SET trang_thai = 'da_ket_thuc' 
        WHERE trang_thai IN ('sap_dien_ra', 'dang_da') 
        AND NOW() >= DATE_ADD(thoi_gian, INTERVAL 120 MINUTE)
    ");
} catch (PDOException $e) {}

// logic
$league_filter_sql = "";
$teams_params = [];

if (isset($_GET['league_id']) && !empty($_GET['league_id'])) {
    $league_filter_sql = " WHERE d.id_giaidau = :league_id ";
    $teams_params['league_id'] = $_GET['league_id'];
}

$sql_teams = "SELECT d.id, d.ten_doi, 
                   (SELECT COUNT(*) FROM tbl_trandau t 
                    WHERE t.trang_thai = 'sap_dien_ra' 
                    AND (t.id_doi_nha = d.id OR t.id_doi_khach = d.id)) AS so_tran
              FROM tbl_doibong d 
              $league_filter_sql
              HAVING so_tran > 0 
              ORDER BY d.ten_doi ASC";

$stmt_teams = $conn->prepare($sql_teams);
$stmt_teams->execute($teams_params);
$teams_list = $stmt_teams->fetchAll(PDO::FETCH_ASSOC);

// bộ lọc 
$where_clause = "t.trang_thai = 'sap_dien_ra'";
$params = [];

if (isset($_GET['league_id'])) {
    $where_clause .= " AND t.id_giaidau = :league_id";
    $params['league_id'] = $_GET['league_id'];
    $active_menu = 'league_' . $_GET['league_id'];
} else {
    $active_menu = 'upcoming';
}

if (!empty($_GET['from_date'])) {
    $where_clause .= " AND DATE(t.thoi_gian) >= :from_date";
    $params[':from_date'] = $_GET['from_date'];
}
if (!empty($_GET['to_date'])) {
    $where_clause .= " AND DATE(t.thoi_gian) <= :to_date";
    $params[':to_date'] = $_GET['to_date'];
}

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

if (empty($_GET['from_date']) && empty($_GET['to_date']) && empty($selected_teams) && empty($_GET['league_id'])) {
    $where_clause .= " AND DATE(t.thoi_gian) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
    $page_title = "CÁC TRẬN ĐẤU TRONG TUẦN TỚI";
} else {
    $page_title = "KẾT QUẢ TÌM KIẾM";
}
?>