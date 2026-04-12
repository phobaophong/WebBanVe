<?php
session_start();

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_id'])) {
    $id_trandau = isset($_GET['id_trandau']) ? $_GET['id_trandau'] : '';
    $_SESSION['error'] = "Bạn cần đăng nhập để tiến hành đặt vé!";
    if ($id_trandau != '') {
        header("Location: login.php?redirect=checkout.php?id_trandau=" . $id_trandau);
    } else {
        header("Location: login.php");
    }
    exit();
}

require_once '../config/database.php'; 

// 2. LẤY THÔNG TIN TRẬN ĐẤU VÀ VÉ
$id_trandau = isset($_GET['id_trandau']) ? (int)$_GET['id_trandau'] : 0;

if ($id_trandau <= 0) {
    header("Location: ../index.php");
    exit();
}

// Lấy thông tin chi tiết trận đấu
$sql_match = "SELECT t.*, g.ten_giai, dn.ten_doi AS ten_nha, dk.ten_doi AS ten_khach 
              FROM tbl_trandau t
              JOIN tbl_giaidau g ON t.id_giaidau = g.id
              JOIN tbl_doibong dn ON t.id_doi_nha = dn.id
              JOIN tbl_doibong dk ON t.id_doi_khach = dk.id
              WHERE t.id = :id_trandau LIMIT 1";
$stmt = $conn->prepare($sql_match);
$stmt->execute(['id_trandau' => $id_trandau]);
$match = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    die("<div class='container mt-5'><h3 class='text-danger text-center'>Không tìm thấy trận đấu!</h3></div>");
}

// Lấy danh sách các hạng vé của trận đấu này (Chỉ lấy những hạng vé còn số lượng > 0)
$sql_tickets = "SELECT v.*, h.ten_hang 
                FROM tbl_ve v 
                JOIN tbl_hangve h ON v.id_hangve = h.id 
                WHERE v.id_trandau = :id_trandau AND v.so_luong_con > 0";
$stmt_tickets = $conn->prepare($sql_tickets);
$stmt_tickets->execute(['id_trandau' => $id_trandau]);
$tickets = $stmt_tickets->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container">
    <div class="checkout-container">
        <h2 class="auth-title mb-4">🎟️ XÁC NHẬN ĐẶT VÉ</h2>
        
        <?php 
        if (isset($_SESSION['error'])) {
            echo "<div class='alert-error'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']); 
        }
        ?>

        <div class="match-summary">
            <h5 class="text-uppercase text-primary font-weight-bold"><?php echo htmlspecialchars($match['ten_giai']); ?></h5>
            <h3 class="mt-2 mb-3 font-weight-bold checkout-match-title">
                <?php echo htmlspecialchars($match['ten_nha']); ?> <span class="text-dark">VS</span> <?php echo htmlspecialchars($match['ten_khach']); ?>
            </h3>
            <p class="mb-1"><b>⏰ Thời gian:</b> <?php echo date('H:i - d/m/Y', strtotime($match['thoi_gian'])); ?></p>
            <p class="mb-0"><b>🏟️ Sân vận động:</b> <?php echo htmlspecialchars($match['san_van_dong']); ?></p>
        </div>

        <?php if (count($tickets) > 0): ?>
            <form action="../actions/process_buy.php" method="POST" id="checkoutForm">
                <input type="hidden" name="id_trandau" value="<?php echo $match['id']; ?>">
                
                <div class="form-group">
                    <label class="font-weight-bold">1. Chọn hạng vé <span class="text-danger">*</span></label>
                    <select class="form-control" name="id_ve" id="ticketSelect" required>
                        <option value="" data-price="0" data-max="0">-- Vui lòng chọn hạng vé --</option>
                        <?php foreach($tickets as $tk): ?>
                            <option value="<?php echo $tk['id']; ?>" data-price="<?php echo $tk['gia_tien']; ?>" data-max="<?php echo $tk['so_luong_con']; ?>">
                                <?php echo htmlspecialchars($tk['ten_hang']); ?> - Giá: <?php echo number_format($tk['gia_tien'], 0, ',', '.'); ?> VNĐ (Còn <?php echo $tk['so_luong_con']; ?> vé)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">2. Số lượng <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="so_luong" id="ticketQty" value="1" min="1" max="10" required>
                    <small class="form-text text-muted">Mỗi người được mua tối đa 10 vé.</small>
                </div>

                <hr class="mt-4 mb-4">

                <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light border rounded">
                    <h4 class="mb-0">Tổng thanh toán:</h4>
                    <h3 class="mb-0 text-success font-weight-bold" id="totalPrice">0 VNĐ</h3>
                </div>

                <button type="submit" class="btn btn-success btn-block btn-auth" id="btnSubmit" disabled>THANH TOÁN NGAY</button>
                <a href="../index.php" class="btn btn-outline-secondary btn-block btn-auth">Quay lại Trang chủ</a>
            </form>
        <?php else: ?>
            <div class="alert alert-danger text-center">
                <strong>Rất tiếc!</strong> Trận đấu này hiện đã cháy vé hoặc chưa mở bán. Vui lòng chọn trận đấu khác.
            </div>
            <a href="../index.php" class="btn btn-primary btn-block btn-auth">Quay lại Trang chủ</a>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ticketSelect = document.getElementById('ticketSelect');
    const ticketQty = document.getElementById('ticketQty');
    const totalPriceDisplay = document.getElementById('totalPrice');
    const btnSubmit = document.getElementById('btnSubmit');

    function calculateTotal() {
        const selectedOption = ticketSelect.options[ticketSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const maxQty = parseInt(selectedOption.getAttribute('data-max')) || 0;
        let qty = parseInt(ticketQty.value) || 0;

        if (price > 0 && qty > 0) {
            if (qty > maxQty) {
                alert("Số lượng vé bạn chọn vượt quá số vé còn lại trong kho (" + maxQty + " vé).");
                ticketQty.value = maxQty;
                qty = maxQty;
            }
            
            const total = price * qty;
            totalPriceDisplay.textContent = new Intl.NumberFormat('vi-VN').format(total) + " VNĐ";
            btnSubmit.disabled = false;
        } else {
            totalPriceDisplay.textContent = "0 VNĐ";
            btnSubmit.disabled = true;
        }
    }

    if(ticketSelect && ticketQty) {
        ticketSelect.addEventListener('change', calculateTotal);
        ticketQty.addEventListener('input', calculateTotal);
    }
});
</script>

<?php include '../includes/footer.php'; ?>