// Auto-dismiss alert
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);

// Konfirmasi hapus
function konfirmasiHapus(url) {
    if (confirm('Yakin ingin menghapus data ini?')) window.location.href = url;
}

// Toggle sidebar mobile
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', () => sidebar.classList.toggle('show'));
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 992 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    }
    
    // Navbar scroll effect
    const nav = document.querySelector('.landing-nav');
    if (nav) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.style.padding = '10px 0';
                nav.style.boxShadow = '0 4px 30px rgba(15,23,42,0.1)';
            } else {
                nav.style.padding = '14px 0';
                nav.style.boxShadow = '0 1px 20px rgba(15,23,42,0.06)';
            }
        });
    }
});