<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'warga') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Status Pengajuan';
require_once '../includes/header.php';
require_once '../includes/sidebar_warga.php';

$user_id = $_SESSION['user_id'];
$penduduk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penduduk WHERE user_id=$user_id"));

$data = null;
if ($penduduk) {
    $data = mysqli_query($conn, "SELECT p.*, js.nama_surat, js.kode_surat FROM pengajuan_surat p
        JOIN jenis_surat js ON p.jenis_surat_id = js.id
        WHERE p.penduduk_id={$penduduk['id']} ORDER BY p.tanggal_ajuan DESC");
}
?>

<main class="main-content">
    <h2 class="fw-bold mb-4">Status Pengajuan Surat</h2>

    <div class="card-stat p-3">
        <?php if (!$data || mysqli_num_rows($data) == 0): ?>
            <p class="text-center text-muted py-4">
                <i class="bi bi-inbox" style="font-size: 3rem; display:block; opacity:0.3;"></i>
                Belum ada pengajuan surat. <a href="ajukan_surat.php">Ajukan sekarang</a>
            </p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>No</th><th>Jenis Surat</th><th>Tanggal</th><th>Status</th><th>Catatan Admin</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                    <?php $no=1; while ($row = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_surat']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['tanggal_ajuan'])) ?></td>
                            <td><span class="badge-status badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                            <td><small><?= htmlspecialchars($row['catatan_admin'] ?? '-') ?></small></td>
                            <td>
                                <?php if ($row['status'] == 'disetujui'): ?>
                                    <a href="surat_saya.php" class="btn btn-sm btn-green"><i class="bi bi-download"></i> Unduh</a>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>