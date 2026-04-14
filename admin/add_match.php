<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$base_url = "/WEBBANVE";

$leagues = $conn->query("SELECT * FROM tbl_giaidau ORDER BY ten_giai ASC")->fetchAll(PDO::FETCH_ASSOC);
$teams = $conn->query("SELECT * FROM tbl_doibong ORDER BY ten_doi ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Trận Đấu - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="admin-body">

    <header class="admin-header">
        <h1 class="admin-title">
            <img src="<?php echo $base_url; ?>/assets/images/system/icon-add.png" class="sys-icon" alt="icon"> THÊM TRẬN ĐẤU MỚI
        </h1>
        <div class="admin-nav-links">
            <a href="index.php">
                <img src="<?php echo $base_url; ?>/assets/images/system/icon-back.png" class="sys-icon" alt="icon"> Trở về Bảng Điều Khiển
            </a>
        </div>
    </header>

    <div class="container mt-5">
        <div class="admin-table-container admin-form-container">
            
            <?php 
            if (isset($_SESSION['error'])) {
                echo "<div class='alert-error'>" . htmlspecialchars($_SESSION['error']) . "</div>";
                unset($_SESSION['error']); 
            }
            ?>

            <ul class="nav nav-tabs admin-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#manual">
                        <img src="<?php echo $base_url; ?>/assets/images/system/icon-edit.png" class="sys-icon" alt="icon"> THÊM THỦ CÔNG
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#upload">
                        <img src="<?php echo $base_url; ?>/assets/images/system/icon-file.png" class="sys-icon" alt="icon"> THÊM BẰNG FILE (CSV)
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="manual">
                    <form action="../actions/process_add_match.php" method="POST">
                        <input type="hidden" name="action_type" value="manual">

                        <div class="form-group">
                            <label class="font-weight-bold">Giải Đấu</label>
                            <select name="id_giaidau" id="leagueSelect" class="form-control" required>
                                <option value="">-- Chọn Giải đấu --</option>
                                <?php foreach($leagues as $lg): ?>
                                    <option value="<?php echo $lg['id']; ?>"><?php echo htmlspecialchars($lg['ten_giai']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold text-danger">Đội Nhà</label>
                                <select name="id_doi_nha" id="homeTeam" class="form-control" required disabled>
                                    <option value="">-- Chọn Đội nhà --</option>
                                    <?php foreach($teams as $t): ?>
                                        <option value="<?php echo $t['id']; ?>" data-league-id="<?php echo $t['id_giaidau']; ?>">
                                            <?php echo htmlspecialchars($t['ten_doi']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold text-primary">Đội Khách</label>
                                <select name="id_doi_khach" id="awayTeam" class="form-control" required disabled>
                                    <option value="">-- Chọn Đội khách --</option>
                                    <?php foreach($teams as $t): ?>
                                        <option value="<?php echo $t['id']; ?>" data-league-id="<?php echo $t['id_giaidau']; ?>">
                                            <?php echo htmlspecialchars($t['ten_doi']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">Thời gian thi đấu</label>
                                <input type="datetime-local" name="thoi_gian" class="form-control" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">Trạng thái</label>
                                <select name="trang_thai" class="form-control" required>
                                    <option value="sap_dien_ra">Sắp diễn ra</option>
                                    <option value="dang_da">Đang đá</option>
                                    <option value="da_ket_thuc">Đã kết thúc</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Sân vận động</label>
                            <input type="text" name="san_van_dong" class="form-control" placeholder="VD: SVĐ Old Trafford" required>
                        </div>

                        <button type="submit" class="btn btn-success btn-block btn-auth">LƯU TRẬN ĐẤU</button>
                    </form>
                </div>

                <div class="tab-pane fade" id="upload">
                    <div class="alert alert-info">
                        <strong>Hướng dẫn:</strong> File upload phải là định dạng <code>.csv</code>. Cấu trúc các cột theo thứ tự: <br>
                        <code>ID_GiảiĐấu, ID_ĐộiNhà, ID_ĐộiKhách, ThờiGian (YYYY-MM-DD HH:MM:SS), SânVậnĐộng, TrạngThái</code>. <br>
                        <i>Lưu ý: Không để dòng tiêu đề (Header) trong file CSV.</i>
                    </div>

                    <form action="../actions/process_add_match.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action_type" value="csv">
                        
                        <div class="form-group mt-4">
                            <label class="font-weight-bold">Chọn file CSV từ máy tính</label>
                            <input type="file" name="file_csv" class="form-control-file" accept=".csv" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-auth mt-4">TIẾN HÀNH IMPORT DỮ LIỆU</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const leagueSelect = document.getElementById('leagueSelect');
        const homeTeam = document.getElementById('homeTeam');
        const awayTeam = document.getElementById('awayTeam');

        leagueSelect.addEventListener('change', function() {
            const selectedLeague = this.value;
            if(selectedLeague === "") {
                homeTeam.disabled = true;
                awayTeam.disabled = true;
                return;
            }
            homeTeam.disabled = false;
            awayTeam.disabled = false;
            homeTeam.value = "";
            awayTeam.value = "";
            filterOptionsByLeague(homeTeam, selectedLeague);
            filterOptionsByLeague(awayTeam, selectedLeague);
        });

        homeTeam.addEventListener('change', function() {
            const selectedHome = this.value;
            const currentLeague = leagueSelect.value;
            filterOptionsByLeague(awayTeam, currentLeague, selectedHome);
        });

        function filterOptionsByLeague(selectElement, leagueId, excludeId = null) {
            const options = selectElement.querySelectorAll('option');
            options.forEach(opt => {
                if (opt.value === "") return;
                const optLeague = opt.getAttribute('data-league-id');
                const isExcluded = (opt.value === excludeId);

                if (optLeague === leagueId && !isExcluded) {
                    opt.style.display = 'block';
                    opt.disabled = false;
                } else {
                    opt.style.display = 'none';
                    opt.disabled = true;
                }
            });
        }
    });
    </script>
</body>
</html>