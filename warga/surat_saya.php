<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'warga') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Surat Saya';
require_once '../includes/header.php';
require_once '../includes/sidebar_warga.php';

$user_id = $_SESSION['user_id'];
$penduduk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penduduk WHERE user_id=$user_id"));

$data = null;
if ($penduduk) {
    $data = mysqli_query($conn, "SELECT st.*, js.nama_surat FROM surat_terbit st
        JOIN pengajuan_surat p ON st.pengajuan_id = p.id
        JOIN jenis_surat js ON p.jenis_surat_id = js.id
        WHERE p.penduduk_id = {$penduduk['id']}
        ORDER BY st.tanggal_terbit DESC");
}
?>

<main class="main-content">
    <h2 class="fw-bold mb-4">Surat Saya</h2>

    <div class="card-stat p-3">
        <?php if (!$data || mysqli_num_rows($data) == 0): ?>
            <p class="text-center text-muted py-4">Belum ada surat terbit</p>
        <?php else: ?>
            <div class="row g-3">
            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <div class="col-md-6">
                    <div class="card-stat p-3">
                        <h6 class="fw-bold mb-2"><?= htmlspecialchars($row['nama_surat']) ?></h6>
                        <p class="small text-muted mb-1">No. Surat: <strong><?= $row['nomor_surat'] ?></strong></p>
                        <p class="small text-muted mb-3">Tanggal: <?= tanggalIndo($row['tanggal_terbit']) ?></p>
                        <a href="download_surat.php?id=<?= $row['id'] ?>" class="btn btn-green btn-sm">
                            <i class="bi bi-download"></i> Unduh PDF
                        </a>
                        <a href="<?= BASE_URL ?>verifikasi/index.php?hash=<?= $row['hash_verifikasi'] ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-qr-code"></i> Verifikasi
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>