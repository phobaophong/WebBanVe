<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký - Vé World Cup</title>
</head>
<body>
    <h2>Đăng ký tài khoản (Tặng ngay 1000$)</h2>
    
    <form action="../actions/process_register.php" method="POST">
        <div>
            <label>Tên đăng nhập:</label>
            <input type="text" name="txt_username" required>
        </div>
        <br>
        <div>
            <label>Mật khẩu:</label>
            <input type="password" name="txt_password" required>
        </div>
        <br>
        <div>
            <label>Họ và tên:</label>
            <input type="text" name="txt_hoten" required>
        </div>
        <br>
        <button type="submit">Đăng ký ngay</button>
    </form>
</body>
</html>