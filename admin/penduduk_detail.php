<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Detail Penduduk';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

$id = (int)$_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT p.*, k.kepala_keluarga FROM penduduk p
    LEFT JOIN kartu_keluarga k ON p.no_kk=k.no_kk WHERE p.id=$id"));
if (!$data) redirect('penduduk.php');

$mutasi_masuk = mysqli_query($conn, "SELECT * FROM mutasi_masuk WHERE penduduk_id=$id ORDER BY tanggal_mutasi DESC");
$mutasi_keluar = mysqli_query($conn, "SELECT * FROM mutasi_keluar WHERE penduduk_id=$id ORDER BY tanggal_mutasi DESC");
?>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Detail Penduduk</h2>
        <a href="penduduk.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card-stat p-4">
                <h5 class="fw-bold mb-3">Data Pribadi</h5>
                <table class="table table-borderless">
                    <tr><th width="200">NIK</th><td><?= $data['nik'] ?></td></tr>
                    <tr><th>No. KK</th><td><?= $data['no_kk'] ?></td></tr>
                    <tr><th>Nama Lengkap</th><td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
                    <tr><th>Jenis Kelamin</th><td><?= $data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
                    <tr><th>Tempat / Tgl Lahir</th><td><?= $data['tempat_lahir'] ?>, <?= tanggalIndo($data['tanggal_lahir']) ?></td></tr>
                    <tr><th>Agama</th><td><?= $data['agama'] ?></td></tr>
                    <tr><th>Status Perkawinan</th><td><?= $data['status_perkawinan'] ?></td></tr>
                    <tr><th>Pekerjaan</th><td><?= $data['pekerjaan'] ?></td></tr>
                    <tr><th>Alamat</th><td><?= $data['alamat'] ?> RT <?= $data['rt'] ?>/RW <?= $data['rw'] ?></td></tr>
                    <tr><th>Hubungan Keluarga</th><td><?= $data['hubungan_keluarga'] ?></td></tr>
                    <tr><th>Status Kependudukan</th><td>
                        <?php $color = $data['status_kependudukan']=='aktif'?'success':($data['status_kependudukan']=='pindah'?'warning':'secondary'); ?>
                        <span class="badge bg-<?= $color ?>"><?= ucfirst($data['status_kependudukan']) ?></span>
                    </td></tr>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-stat p-4 mb-3">
                <h5 class="fw-bold mb-3">Aksi Cepat</h5>
                <a href="penduduk_edit.php?id=<?= $data['id'] ?>" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit Data
                </a>
                <a href="mutasi_tambah.php?penduduk_id=<?= $data['id'] ?>" class="btn btn-green w-100">
                    <i class="bi bi-arrow-left-right"></i> Catat Mutasi
                </a>
            </div>

            <div class="card-stat p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-arrow-down-circle text-success"></i> Mutasi Masuk</h6>
                <?php if (mysqli_num_rows($mutasi_masuk) == 0): ?>
                    <p class="text-muted small">Belum ada riwayat</p>
                <?php else: while ($m = mysqli_fetch_assoc($mutasi_masuk)): ?>
                    <div class="border-bottom pb-2 mb-2">
                        <small class="fw-bold"><?= ucwords(str_replace('_',' ',$m['jenis_mutasi'])) ?></small><br>
                        <small class="text-muted"><?= tanggalIndo($m['tanggal_mutasi']) ?></small>
                    </div>
                <?php endwhile; endif; ?>

                <h6 class="fw-bold mb-3 mt-4"><i class="bi bi-arrow-up-circle text-danger"></i> Mutasi Keluar</h6>
                <?php if (mysqli_num_rows($mutasi_keluar) == 0): ?>
                    <p class="text-muted small">Belum ada riwayat</p>
                <?php else: while ($m = mysqli_fetch_assoc($mutasi_keluar)): ?>
                    <div class="border-bottom pb-2 mb-2">
                        <small class="fw-bold"><?= ucwords(str_replace('_',' ',$m['jenis_mutasi'])) ?></small><br>
                        <small class="text-muted"><?= tanggalIndo($m['tanggal_mutasi']) ?></small>
                    </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>