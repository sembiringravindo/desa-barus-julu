<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Mutasi Warga';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

$tab = $_GET['tab'] ?? 'masuk';

$masuk = mysqli_query($conn, "SELECT m.*, p.nama_lengkap, p.nik FROM mutasi_masuk m
    JOIN penduduk p ON m.penduduk_id = p.id ORDER BY m.tanggal_mutasi DESC");
$keluar = mysqli_query($conn, "SELECT m.*, p.nama_lengkap, p.nik FROM mutasi_keluar m
    JOIN penduduk p ON m.penduduk_id = p.id ORDER BY m.tanggal_mutasi DESC");
?>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Mutasi Warga</h2>
            <p class="text-muted mb-0">Catatan warga masuk & keluar</p>
        </div>
        <a href="mutasi_tambah.php" class="btn btn-green"><i class="bi bi-plus-circle"></i> Catat Mutasi</a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link <?= $tab=='masuk'?'active':'' ?>" href="?tab=masuk">Mutasi Masuk</a></li>
        <li class="nav-item"><a class="nav-link <?= $tab=='keluar'?'active':'' ?>" href="?tab=keluar">Mutasi Keluar</a></li>
    </ul>

    <div class="card-stat p-3">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th><th>Nama</th><th>NIK</th><th>Jenis Mutasi</th>
                        <th>Tanggal</th><th><?= $tab=='masuk'?'Asal Desa':'Tujuan Desa' ?></th><th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $data = $tab == 'masuk' ? $masuk : $keluar;
                $no = 1;
                if (mysqli_num_rows($data) == 0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data mutasi</td></tr>
                <?php else: while ($row = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><small><?= $row['nik'] ?></small></td>
                        <td>
                            <?php $color = (strpos($row['jenis_mutasi'],'kelahiran')!==false||strpos($row['jenis_mutasi'],'pindah_datang')!==false)?'success':'danger'; ?>
                            <span class="badge bg-<?= $color ?>"><?= ucwords(str_replace('_',' ',$row['jenis_mutasi'])) ?></span>
                        </td>
                        <td><?= tanggalIndo($row['tanggal_mutasi']) ?></td>
                        <td><?= htmlspecialchars($tab=='masuk' ? ($row['asal_desa']??'-') : ($row['tujuan_desa']??'-')) ?></td>
                        <td><small><?= htmlspecialchars($row['keterangan']??'-') ?></small></td>
                    </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>