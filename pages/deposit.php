<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'khach_hang') {
    header("Location: login.php");
    exit();
}

$base_url = "/WEBBANVE";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container">
    <div class="user-page-container deposit-wrapper">
        <h2 class="text-center font-weight-bold mb-4 deposit-title"> NẠP TIỀN TÀI KHOẢN</h2>
        
        <div class="alert alert-info text-center mb-4">
            Số dư hiện tại của bạn: <strong><?php echo number_format(isset($_SESSION['so_du']) ? $_SESSION['so_du'] : 0, 0, ',', '.'); ?> VNĐ</strong>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class='alert-error'><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class='alert alert-success alert-custom-success'><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
        <?php endif; ?>

        <form action="../actions/process_deposit.php" method="POST">
            <div class="form-group">
                <label class="font-weight-bold">Chọn mệnh giá nạp nhanh:</label>
                <div class="preset-amounts">
                    <button type="button" class="btn-preset" data-amount="100000">100.000đ</button>
                    <button type="button" class="btn-preset" data-amount="200000">200.000đ</button>
                    <button type="button" class="btn-preset" data-amount="500000">500.000đ</button>
                    <button type="button" class="btn-preset" data-amount="1000000">1.000.000đ</button>
                    <button type="button" class="btn-preset" data-amount="2000000">2.000.000đ</button>
                    <button type="button" class="btn-preset" data-amount="5000000">5.000.000đ</button>
                </div>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Hoặc nhập số tiền muốn nạp (VNĐ):</label>
                <input type="number" name="so_tien" id="inputAmount" class="form-control form-control-lg text-danger font-weight-bold" min="10000" step="10000" required placeholder="Nhập số tiền...">
            </div>

            <button type="submit" class="btn btn-success btn-block btn-lg font-weight-bold mt-4 btn-rounded">XÁC NHẬN NẠP TIỀN</button>
        </form>
        <div class="text-center">
            <a href="../index.php" class="btn-back-home"> Quay lại Trang chủ</a>
        </div>
    </div>
</div>

<script>
// Javascript giúp bấm nút điền tiền tự động
document.querySelectorAll('.btn-preset').forEach(button => {
    button.addEventListener('click', function() {
        // Xóa class active của các nút khác
        document.querySelectorAll('.btn-preset').forEach(btn => btn.classList.remove('active'));
        // Thêm class active cho nút vừa bấm
        this.classList.add('active');
        // Điền giá trị vào ô input
        document.getElementById('inputAmount').value = this.getAttribute('data-amount');
    });
});
</script>

<?php include '../includes/footer.php'; ?>