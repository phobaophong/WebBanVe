// Đảm bảo toàn bộ HTML đã tải xong thì mới chạy code JS
document.addEventListener("DOMContentLoaded", function() {
    
    // TÍNH NĂNG 1: Tự động ẩn các thông báo (alert) sau 4 giây
    const alerts = document.querySelectorAll('.alert-error, [style*="background-color: #d4edda"]');
    
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                // Thêm hiệu ứng mờ dần
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                
                // Xóa hẳn thẻ HTML khỏi giao diện sau khi mờ xong
                setTimeout(() => alert.remove(), 500);
            });
        }, 4000); // 4000 ms = 4 giây
    }

    // TÍNH NĂNG 2: Sẽ code hàm tính tổng tiền mua vé ở đây trong bước tới...
    // ...
});