<?php
session_start();

// BỨC TƯỜNG LỬA
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=need_login");
    exit();
}

require_once '../config/database.php';

// XỬ LÝ LẤY DỮ LIỆU TRẬN ĐẤU
if (!isset($_GET['id_trandau']) || empty($_GET['id_trandau'])) {
    header("Location: match_list.php");
    exit();
}
$id_trandau = $_GET['id_trandau'];

// Lấy thông tin trận đấu
$stmt_match = $conn->prepare("SELECT * FROM tbl_trandau WHERE id = ?");
$stmt_match->execute([$id_trandau]);
$match = $stmt_match->fetch(PDO::FETCH_ASSOC);

// ĐÃ SỬA: Dùng JOIN để lấy được tên hạng vé (Category 1, VIP...) từ bảng tbl_hangve
$stmt_tickets = $conn->prepare("
    SELECT v.*, h.ten_hang 
    FROM tbl_ve v 
    JOIN tbl_hangve h ON v.id_hangve = h.id 
    WHERE v.id_trandau = ? 
    ORDER BY v.gia_tien DESC
");
$stmt_tickets->execute([$id_trandau]);
$tickets = $stmt_tickets->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php'; 
include '../includes/navbar.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-4 text-center">XÁC NHẬN MUA VÉ</h2>

            <div class="card bg-dark text-white text-center mb-4 shadow p-4" style="border-radius: 15px;">
                <h5 class="text-warning fw-bold mb-3">VÒNG BẢNG - BẢNG <?= $match['bang_dau'] ?></h5>
                <h2 class="fw-bold mb-3">
                    <?= htmlspecialchars($match['doi_nha']) ?> <span class="text-danger fst-italic mx-3">VS</span> <?= htmlspecialchars($match['doi_khach']) ?>
                </h2>
                <p class="mb-1">🕒 <b>Thời gian:</b> <?= date('d/m/Y - H:i', strtotime($match['thoi_gian'])) ?></p>
                <p class="mb-0">📍 <b>Sân vận động:</b> <?= htmlspecialchars($match['san_van_dong']) ?></p>
            </div>

            <div class="card p-4 shadow-sm" style="border-radius: 15px;">
                <form action="../actions/process_buy.php" method="POST">
                    <input type="hidden" name="id_trandau" value="<?= $match['id'] ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold">1. Chọn Hạng Vé</label>
                        <select class="form-select form-select-lg" name="id_ve" id="ticket_select" onchange="calculateTotal()" required>
                            <option value="" data-price="0" selected disabled>-- Vui lòng chọn loại vé --</option>
                            <?php foreach ($tickets as $t): ?>
                                <option value="<?= $t['id'] ?>" data-price="<?= $t['gia_tien'] ?>" <?= $t['so_luong_con'] <= 0 ? 'disabled' : '' ?>>
                                    <?= $t['ten_hang'] ?> - Giá: <?= number_format($t['gia_tien'], 0, ',', '.') ?> VNĐ 
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">2. Số Lượng</label>
                        <input type="number" name="so_luong" id="ticket_qty" class="form-control form-control-lg" value="1" min="1" max="5" onchange="calculateTotal()" required>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold m-0">Tổng Tiền:</h4>
                        <h2 class="fw-bold text-danger m-0" id="total_price">0 VNĐ</h2>
                    </div>

                    <button type="submit" name="btn_buy_ticket" class="btn btn-danger btn-lg w-100 fw-bold rounded-pill">XÁC NHẬN THANH TOÁN</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function calculateTotal() {
        var select = document.getElementById('ticket_select');
        var price = select.options[select.selectedIndex].getAttribute('data-price');
        var qty = document.getElementById('ticket_qty').value;
        if (price > 0) {
            document.getElementById('total_price').innerText = new Intl.NumberFormat('vi-VN').format(price * qty) + ' VNĐ';
        }
    }
</script>

<?php 
include '../includes/footer.php'; 
?>