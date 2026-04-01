// public/js/script.js
document.addEventListener('DOMContentLoaded', function() {
    console.log("JavaScript loaded");
    // Xác nhận trước khi xóa
    const deleteLinks = document.querySelectorAll('a[href*="delete"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Bạn có chắc chắn muốn xóa?')) {
                e.preventDefault();
            }
        });
    });
});