<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Pengajuan Surat';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

$filter = $_GET['status'] ?? '';
$where = $filter ? "WHERE p.status='$filter'" : '';

$q = "SELECT p.*, pd.nama_lengkap, pd.nik, js.nama_surat FROM pengajuan_surat p
      JOIN penduduk pd ON p.penduduk_id = pd.id
      JOIN jenis_surat js ON p.jenis_surat_id = js.id
      $where ORDER BY p.tanggal_ajuan DESC";
$result = mysqli_query($conn, $q);
?>

<main class="main-content">
    <h2 class="fw-bold mb-4">Pengajuan Surat</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="?" class="btn btn-sm <?= $filter==''?'btn-green':'btn-outline-secondary' ?>">Semua</a>
        <a href="?status=menunggu" class="btn btn-sm <?= $filter=='menunggu'?'btn-green':'btn-outline-secondary' ?>">Menunggu</a>
        <a href="?status=diproses" class="btn btn-sm <?= $filter=='diproses'?'btn-green':'btn-outline-secondary' ?>">Diproses</a>
        <a href="?status=disetujui" class="btn btn-sm <?= $filter=='disetujui'?'btn-green':'btn-outline-secondary' ?>">Disetujui</a>
        <a href="?status=ditolak" class="btn btn-sm <?= $filter=='ditolak'?'btn-green':'btn-outline-secondary' ?>">Ditolak</a>
    </div>

    <div class="card-stat p-3">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>No</th><th>Nama Pemohon</th><th>NIK</th><th>Jenis Surat</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php $no=1; if (mysqli_num_rows($result)==0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada pengajuan</td></tr>
                <?php else: while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><small><?= $row['nik'] ?></small></td>
                        <td><?= htmlspecialchars($row['nama_surat']) ?></td>
                        <td><small><?= date('d/m/Y H:i', strtotime($row['tanggal_ajuan'])) ?></small></td>
                        <td><span class="badge-status badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                        <td><a href="pengajuan_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-green">Proses</a></td>
                    </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>