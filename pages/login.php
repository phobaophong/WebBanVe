<?php 
include '../includes/header.php'; 
include '../includes/navbar.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">Đăng Nhập Hệ Thống</h4>
                </div>
                <div class="card-body p-4">
                    
                    <form action="../actions/process_login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên đăng nhập</label>
                            <input type="text" name="txt_username" class="form-control" placeholder="Nhập tài khoản..." required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Mật khẩu</label>
                            <input type="password" name="txt_password" class="form-control" placeholder="Nhập mật khẩu..." required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Đăng Nhập Vào Mua Vé</button>
                    </form>

                    <div class="text-center mt-3">
                        <p>Chưa có tài khoản? <a href="register.php" class="text-decoration-none">Đăng ký ngay</a></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>