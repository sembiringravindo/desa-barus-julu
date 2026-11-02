<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Surat Terbit';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

$data = mysqli_query($conn, "SELECT st.*, js.nama_surat, pd.nama_lengkap FROM surat_terbit st
    JOIN pengajuan_surat p ON st.pengajuan_id = p.id
    JOIN penduduk pd ON p.penduduk_id = pd.id
    JOIN jenis_surat js ON p.jenis_surat_id = js.id
    ORDER BY st.tanggal_terbit DESC");
?>

<main class="main-content">
    <h2 class="fw-bold mb-4">Surat Terbit</h2>

    <div class="card-stat p-3">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>No</th><th>Nomor Surat</th><th>Nama</th><th>Jenis Surat</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php $no=1; if (mysqli_num_rows($data)==0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada surat terbit</td></tr>
                <?php else: while ($row = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= $row['nomor_surat'] ?></strong></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($row['nama_surat']) ?></td>
                        <td><?= tanggalIndo($row['tanggal_terbit']) ?></td>
                        <td>
                            <a href="cetak_surat.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-sm btn-green">
                                <i class="bi bi-printer"></i> Cetak PDF
                            </a>
                            <a href="<?= BASE_URL ?>verifikasi/index.php?hash=<?= $row['hash_verifikasi'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-qr-code"></i> QR
                            </a>
                        </td>
                    </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>