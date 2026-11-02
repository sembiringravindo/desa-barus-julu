<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Tambah Penduduk';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

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
    
    $cek = mysqli_query($conn, "SELECT id FROM penduduk WHERE nik='$nik'");
    if (mysqli_num_rows($cek) > 0) {
        $error = 'NIK sudah terdaftar!';
    } else {
        $cek_kk = mysqli_query($conn, "SELECT id FROM kartu_keluarga WHERE no_kk='$no_kk'");
        if (mysqli_num_rows($cek_kk) == 0) {
            mysqli_query($conn, "INSERT INTO kartu_keluarga (no_kk, kepala_keluarga, alamat, rt, rw) 
                                 VALUES ('$no_kk', '$nama', '$alamat', '$rt', '$rw')");
        }
        
        $q = "INSERT INTO penduduk (nik, no_kk, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, agama, status_perkawinan, pekerjaan, alamat, rt, rw, hubungan_keluarga)
              VALUES ('$nik', '$no_kk', '$nama', '$jk', '$tempat', '$tgl', '$agama', '$kawin', '$kerja', '$alamat', '$rt', '$rw', '$hub')";
        
        if (mysqli_query($conn, $q)) redirect('penduduk.php?msg=Data berhasil ditambahkan');
        else $error = 'Gagal: ' . mysqli_error($conn);
    }
}
?>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Tambah Data Penduduk</h2>
        <a href="penduduk.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="card-stat p-4">
        <form method="POST">
            <h6 class="fw-bold text-muted mb-3">DATA PRIBADI</h6>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">NIK *</label>
                    <input type="text" name="nik" class="form-control" maxlength="16" required></div>
                <div class="col-md-6"><label class="form-label">No. KK *</label>
                    <input type="text" name="no_kk" class="form-control" maxlength="16" required></div>
                <div class="col-md-6"><label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select></div>
                <div class="col-md-6"><label class="form-label">Tempat Lahir *</label>
                    <input type="text" name="tempat_lahir" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" class="form-control" required></div>
            </div>
            <hr class="my-4">
            <h6 class="fw-bold text-muted mb-3">DATA LAINNYA</h6>
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Agama *</label>
                    <select name="agama" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option>Islam</option><option>Kristen</option><option>Katolik</option>
                        <option>Hindu</option><option>Buddha</option><option>Konghucu</option>
                    </select></div>
                <div class="col-md-4"><label class="form-label">Status Perkawinan *</label>
                    <select name="status_perkawinan" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option>Belum Menikah</option><option>Menikah</option>
                        <option>Cerai Hidup</option><option>Cerai Mati</option>
                    </select></div>
                <div class="col-md-4"><label class="form-label">Pekerjaan *</label>
                    <input type="text" name="pekerjaan" class="form-control" required></div>
                <div class="col-md-8"><label class="form-label">Alamat *</label>
                    <input type="text" name="alamat" class="form-control" required></div>
                <div class="col-md-2"><label class="form-label">RT *</label>
                    <input type="text" name="rt" class="form-control" required></div>
                <div class="col-md-2"><label class="form-label">RW *</label>
                    <input type="text" name="rw" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Hubungan Keluarga *</label>
                    <select name="hubungan_keluarga" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option>Kepala Keluarga</option><option>Istri</option><option>Anak</option>
                        <option>Menantu</option><option>Cucu</option><option>Orang Tua</option>
                        <option>Famili Lain</option><option>Lainnya</option>
                    </select></div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-green"><i class="bi bi-save"></i> Simpan</button>
                <a href="penduduk.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>