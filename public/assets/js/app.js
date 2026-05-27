// Bank BTN System - Main JS

// Confirm delete
function confirmDelete(url, name) {
    if (confirm(`Yakin ingin menghapus data "${name}"?`)) {
        window.location.href = url;
    }
}

// Flash message auto-hide
document.addEventListener('DOMContentLoaded', function() {
    const flashes = document.querySelectorAll('.flash-message');
    flashes.forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });

    // Mobile sidebar toggle
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }
});

// Format currency input
function formatRupiah(input) {
    let value = input.value.replace(/\D/g, '');
    input.value = value;
}

// Number formatting display
function numberFormat(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}
