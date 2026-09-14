// Confirm before any delete action
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-delete-confirm')) {
        if (!confirm('Are you sure you want to delete this record? This cannot be undone.')) {
            e.preventDefault();
        }
    }
});

// Auto dismiss alerts after 4 seconds
window.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert-auto-dismiss').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 500);
        }, 4000);
    });
});
