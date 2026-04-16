
document.addEventListener("DOMContentLoaded", function () {

    const alerts = document.querySelectorAll('.alert-error, [style*="background-color: #d4edda"]');

    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                // Thêm hiệu ứng mờ dần
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";


                setTimeout(() => alert.remove(), 500);
            });
        }, 4000);
    }

    const urlParams = window.location.search;
    if (urlParams.includes('league_id') || urlParams.includes('from_date') || urlParams.includes('teams') || urlParams.includes('view=all')) {
        

        setTimeout(function() {

            const mainSection = document.querySelector('main');
            
            if (mainSection) {

                const y = mainSection.getBoundingClientRect().top + window.scrollY - 80;
                

                window.scrollTo({top: y, behavior: 'smooth'});
            }
        }, 100);
    }
});