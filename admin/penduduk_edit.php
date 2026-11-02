<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Edit Penduduk';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

$id = (int)$_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penduduk WHERE id=$id"));
if (!$data) redirect('penduduk.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = clean($conn, $_POST['nik']);
    $no_kk = clean($conn, $_POST['no_kk']);
    $nama = clean($conn, $_POST['nama_lengkap']);
    $jk = clean($conn, $_POST['jenis_kelamin']);
    $tempat = clean($conn, $_POST['tempat_lahir']);
    $tgl = clean($conn, $_POST['tanggal_lahir']);
    $agama = clean($conn, $_POST['agama']);
    $kawin = clean($conn, $_POST['status_perkawinan']);
    $kerja = clean($conn, $_POST['pekerjaan']);
    $alamat = clean($conn, $_POST['alamat']);
    $rt = clean($conn, $_POST['rt']);
    $rw = clean($conn, $_POST['rw']);
    $hub = clean($conn, $_POST['hubungan_keluarga']);
    $status = clean($conn, $_POST['status_kependudukan']);
    
    $q = "UPDATE penduduk SET nik='$nik', no_kk='$no_kk', nama_lengkap='$nama', jenis_kelamin='$jk',
          tempat_lahir='$tempat', tanggal_lahir='$tgl', agama='$agama', status_perkawinan='$kawin',
          pekerjaan='$kerja', alamat='$alamat', rt='$rt', rw='$rw', hubungan_keluarga='$hub',
          status_kependudukan='$status' WHERE id=$id";
    
    if (mysqli_query($conn, $q)) redirect('penduduk.php?msg=Data berhasil diperbarui');
    else $error = 'Gagal: ' . mysqli_error($conn);
}
?>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Edit Data Penduduk</h2>
        <a href="penduduk.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="card-stat p-4">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">NIK *</label>
                    <input type="text" name="nik" class="form-control" value="<?= $data['nik'] ?>" maxlength="16" required></div>
                <div class="col-md-6"><label class="form-label">No. KK *</label>
                    <input type="text" name="no_kk" class="form-control" value="<?= $data['no_kk'] ?>" maxlength="16" required></div>
                <div class="col-md-6"><label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="L" <?= $data['jenis_kelamin']=='L'?'selected':'' ?>>Laki-laki</option>
                        <option value="P" <?= $data['jenis_kelamin']=='P'?'selected':'' ?>>Perempuan</option>
                    </select></div>
                <div class="col-md-6"><label class="form-label">Tempat Lahir *</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="<?= htmlspecialchars($data['tempat_lahir']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="<?= $data['tanggal_lahir'] ?>" required></div>
                <div class="col-md-4"><label class="form-label">Agama *</label>
                    <select name="agama" class="form-select" required>
                        <?php foreach (['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $a): ?>
                            <option <?= $data['agama']==$a?'selected':'' ?>><?= $a ?></option>
                        <?php endforeach; ?>
                    </select></div>
                <div class="col-md-4"><label class="form-label">Status Perkawinan *</label>
                    <select name="status_perkawinan" class="form-select" required>
                        <?php foreach (['Belum Menikah','Menikah','Cerai Hidup','Cerai Mati'] as $s): ?>
                            <option <?= $data['status_perkawinan']==$s?'selected':'' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select></div>
                <div class="col-md-4"><label class="form-label">Pekerjaan *</label>
                    <input type="text" name="pekerjaan" class="form-control" value="<?= htmlspecialchars($data['pekerjaan']) ?>" required></div>
                <div class="col-md-8"><label class="form-label">Alamat *</label>
                    <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($data['alamat']) ?>" required></div>
                <div class="col-md-2"><label class="form-label">RT *</label>
                    <input type="text" name="rt" class="form-control" value="<?= $data['rt'] ?>" required></div>
                <div class="col-md-2"><label class="form-label">RW *</label>
                    <input type="text" name="rw" class="form-control" value="<?= $data['rw'] ?>" required></div>
                <div class="col-md-6"><label class="form-label">Hubungan Keluarga *</label>
                    <select name="hubungan_keluarga" class="form-select" required>
                        <?php foreach (['Kepala Keluarga','Istri','Anak','Menantu','Cucu','Orang Tua','Famili Lain','Lainnya'] as $h): ?>
                            <option <?= $data['hubungan_keluarga']==$h?'selected':'' ?>><?= $h ?></option>
                        <?php endforeach; ?>
                    </select></div>
                <div class="col-md-6"><label class="form-label">Status Kependudukan *</label>
                    <select name="status_kependudukan" class="form-select" required>
                        <?php foreach (['aktif','pindah','meninggal'] as $st): ?>
                            <option value="<?= $st ?>" <?= $data['status_kependudukan']==$st?'selected':'' ?>><?= ucfirst($st) ?></option>
                        <?php endforeach; ?>
                    </select></div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-green"><i class="bi bi-save"></i> Simpan Perubahan</button>
                <a href="penduduk.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>