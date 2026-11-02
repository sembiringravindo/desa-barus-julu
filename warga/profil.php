<?php
require_once '../config/session.php';
$title = 'Profil Saya';
require_once '../includes/header.php';
cekRole('warga');
require_once '../includes/sidebar_warga.php';

$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id"));
$penduduk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penduduk WHERE user_id=$user_id"));
$kk = null;
if ($penduduk) {
    $kk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kartu_keluarga WHERE no_kk='{$penduduk['no_kk']}'"));
}

$sukses = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($conn, $_POST['email']);
    $no_hp = clean($conn, $_POST['no_hp']);
    mysqli_query($conn, "UPDATE users SET email='$email', no_hp='$no_hp' WHERE id=$user_id");
    $sukses = 'Profil berhasil diperbarui';
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id"));
}
?>

<main class="main-content">
    <h2 class="fw-bold mb-4">Profil Saya</h2>
    
    <?php if ($sukses): ?><div class="alert alert-success"><?= $sukses ?></div><?php endif; ?>
    
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card-stat p-4 text-center">
                <div class="icon bg-green mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['nama_lengkap']) ?></h5>
                <p class="text-muted small mb-3">@<?= htmlspecialchars($user['username']) ?></p>
                <span class="badge bg-success">Warga Aktif</span>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card-stat p-4 mb-3">
                <h5 class="fw-bold mb-3">Akun</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-green"><i class="bi bi-save"></i> Simpan</button>
                </form>
            </div>
            
            <?php if ($penduduk): ?>
            <div class="card-stat p-4">
                <h5 class="fw-bold mb-3">Data Kependudukan</h5>
                <table class="table table-borderless mb-0">
                    <tr><th width="180">NIK</th><td><?= $penduduk['nik'] ?></td></tr>
                    <tr><th>No. KK</th><td><?= $penduduk['no_kk'] ?></td></tr>
                    <tr><th>Tempat/Tgl Lahir</th><td><?= htmlspecialchars($penduduk['tempat_lahir']) ?>, <?= tanggalIndo($penduduk['tanggal_lahir']) ?></td></tr>
                    <tr><th>Jenis Kelamin</th><td><?= $penduduk['jenis_kelamin']=='L'?'Laki-laki':'Perempuan' ?></td></tr>
                    <tr><th>Agama</th><td><?= $penduduk['agama'] ?></td></tr>
                    <tr><th>Pekerjaan</th><td><?= htmlspecialchars($penduduk['pekerjaan']) ?></td></tr>
                    <tr><th>Alamat</th><td><?= htmlspecialchars($penduduk['alamat']) ?> RT <?= $penduduk['rt'] ?>/RW <?= $penduduk['rw'] ?></td></tr>
                    <tr><th>Hubungan Keluarga</th><td><?= $penduduk['hubungan_keluarga'] ?></td></tr>
                    <?php if ($kk): ?>
                    <tr><th>Kepala Keluarga</th><td><?= htmlspecialchars($kk['kepala_keluarga']) ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Data penduduk Anda belum terdaftar. Hubungi perangkat desa.
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>