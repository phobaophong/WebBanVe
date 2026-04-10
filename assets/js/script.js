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

    let slideIndex = 0;
    let slideTimer;

    function showSlides(n) {
        let slides = document.getElementsByClassName("banner-slide");
        if (slides.length === 0) return;

        // Vòng lặp: Nếu vượt quá số ảnh thì quay lại ảnh đầu tiên
        if (n >= slides.length) { slideIndex = 0 }
        if (n < 0) { slideIndex = slides.length - 1 }

        // Ẩn tất cả các ảnh
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        // Chỉ hiển thị ảnh hiện tại
        slides[slideIndex].style.display = "block";
    }

    function changeSlide(n) {
        slideIndex += n;
        showSlides(slideIndex);
        resetTimer(); // Nếu người dùng tự bấm thì đếm lại 5 giây từ đầu
    }

    function autoPlaySlide() {
        slideIndex++;
        showSlides(slideIndex);
    }

    function resetTimer() {
        clearInterval(slideTimer);
        slideTimer = setInterval(autoPlaySlide, 5000); // 5000ms = 5 giây
    }

    // Khởi chạy ngay khi tải trang xong
    document.addEventListener("DOMContentLoaded", function () {
        let slides = document.getElementsByClassName("banner-slide");
        if (slides.length > 0) {
            showSlides(slideIndex);
            slideTimer = setInterval(autoPlaySlide, 5000);
        }
    });
});