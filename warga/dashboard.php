<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'warga') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Dashboard Warga';
require_once '../includes/header.php';
require_once '../includes/sidebar_warga.php';

$user_id = $_SESSION['user_id'];
$penduduk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penduduk WHERE user_id=$user_id"));

$total = $pending = $selesai = 0;
if ($penduduk) {
    $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pengajuan_surat WHERE penduduk_id=" . $penduduk['id']))['t'];
    $pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pengajuan_surat WHERE penduduk_id=" . $penduduk['id'] . " AND status IN ('menunggu','diproses')"))['t'];
    $selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pengajuan_surat WHERE penduduk_id=" . $penduduk['id'] . " AND status='disetujui'"))['t'];
}
?>

<main class="main-content">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Halo, <?= htmlspecialchars($_SESSION['nama_lengkap']) ?> 👋</h2>
        <p class="text-muted mb-0">Selamat datang di layanan digital Desa Barus Julu</p>
    </div>

    <?php if (!$penduduk): ?>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> 
            <strong>Data penduduk Anda belum terdaftar.</strong> Silakan hubungi perangkat desa.
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-4">
            <div class="card-stat p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-green me-3"><i class="bi bi-file-plus-fill"></i></div>
                    <div>
                        <h4 class="mb-0 fw-bold"><?= $total ?></h4>
                        <small class="text-muted">Total Pengajuan</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4">
            <div class="card-stat p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-orange me-3"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <h4 class="mb-0 fw-bold"><?= $pending ?></h4>
                        <small class="text-muted">Sedang Diproses</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card-stat p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-blue me-3"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <h4 class="mb-0 fw-bold"><?= $selesai ?></h4>
                        <small class="text-muted">Surat Selesai</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="ajukan_surat.php" class="text-decoration-none">
                <div class="card-stat p-4 text-center">
                    <div class="icon bg-green mx-auto mb-3"><i class="bi bi-plus-circle"></i></div>
                    <h6 class="fw-bold text-dark mb-1">Ajukan Surat</h6>
                    <small class="text-muted">Buat permohonan baru</small>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="status_pengajuan.php" class="text-decoration-none">
                <div class="card-stat p-4 text-center">
                    <div class="icon bg-orange mx-auto mb-3"><i class="bi bi-clock-history"></i></div>
                    <h6 class="fw-bold text-dark mb-1">Lacak Status</h6>
                    <small class="text-muted">Cek pengajuan Anda</small>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="profil.php" class="text-decoration-none">
                <div class="card-stat p-4 text-center">
                    <div class="icon bg-blue mx-auto mb-3"><i class="bi bi-person-circle"></i></div>
                    <h6 class="fw-bold text-dark mb-1">Profil Saya</h6>
                    <small class="text-muted">Lihat data pribadi</small>
                </div>
            </a>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>