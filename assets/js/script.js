// Đảm bảo toàn bộ HTML đã tải xong thì mới chạy code JS
document.addEventListener("DOMContentLoaded", function () {

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
    // ==========================================================================
    // TÍNH NĂNG 2: Tự động giữ vị trí cuộn khi bấm Lọc / Chọn giải đấu
    // ==========================================================================
    // Kiểm tra xem URL có chứa các biến bộ lọc không
    const urlParams = window.location.search;
    if (urlParams.includes('league_id') || urlParams.includes('from_date') || urlParams.includes('teams') || urlParams.includes('view=all')) {
        
        // Đợi 100ms để trình duyệt vẽ xong giao diện rồi mới cuộn cho mượt
        setTimeout(function() {
            // Tìm cột giữa (thẻ <main>) chứa danh sách trận đấu
            const mainSection = document.querySelector('main');
            
            if (mainSection) {
                // Lấy tọa độ của khối main và trừ hao 80px để tiêu đề không bị che mất
                const y = mainSection.getBoundingClientRect().top + window.scrollY - 80;
                
                // Kích hoạt hiệu ứng cuộn mượt (smooth scroll)
                window.scrollTo({top: y, behavior: 'smooth'});
            }
        }, 100);
    }
    // (Đã xóa phần code custom slider bị xung đột với Bootstrap Carousel)
});