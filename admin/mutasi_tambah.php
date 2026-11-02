<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Catat Mutasi';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = (int)$_POST['penduduk_id'];
    $jenis = clean($conn, $_POST['jenis_mutasi']);
    $tgl = clean($conn, $_POST['tanggal_mutasi']);
    $desa = clean($conn, $_POST['desa'] ?? '');
    $ket = clean($conn, $_POST['keterangan'] ?? '');
    
    if (strpos($jenis, 'pindah_datang') !== false || strpos($jenis, 'kelahiran') !== false) {
        $q = "INSERT INTO mutasi_masuk (penduduk_id, jenis_mutasi, tanggal_mutasi, asal_desa, keterangan)
              VALUES ($pid, '$jenis', '$tgl', '$desa', '$ket')";
    } else {
        $q = "INSERT INTO mutasi_keluar (penduduk_id, jenis_mutasi, tanggal_mutasi, tujuan_desa, keterangan)
              VALUES ($pid, '$jenis', '$tgl', '$desa', '$ket')";
        $status = $jenis == 'kematian' ? 'meninggal' : 'pindah';
        mysqli_query($conn, "UPDATE penduduk SET status_kependudukan='$status' WHERE id=$pid");
    }
    
    if (mysqli_query($conn, $q)) redirect('mutasi.php?msg=Mutasi berhasil dicatat');
    else $error = 'Gagal: ' . mysqli_error($conn);
}

$penduduk_list = mysqli_query($conn, "SELECT id, nik, nama_lengkap FROM penduduk WHERE status_kependudukan='aktif' ORDER BY nama_lengkap");
$selected_id = (int)($_GET['penduduk_id'] ?? 0);
?>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Catat Mutasi Warga</h2>
        <a href="mutasi.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="card-stat p-4">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pilih Penduduk *</label>
                    <select name="penduduk_id" class="form-select" required>
                        <option value="">-- Pilih Penduduk --</option>
                        <?php while ($p = mysqli_fetch_assoc($penduduk_list)): ?>
                            <option value="<?= $p['id'] ?>" <?= $selected_id == $p['id'] ? 'selected' : '' ?>>
                                <?= $p['nik'] ?> — <?= htmlspecialchars($p['nama_lengkap']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Mutasi *</label>
                    <select name="jenis_mutasi" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="pindah_datang">Pindah Datang (Masuk)</option>
                        <option value="kelahiran">Kelahiran (Masuk)</option>
                        <option value="pindah_keluar">Pindah Keluar</option>
                        <option value="kematian">Kematian</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Mutasi *</label>
                    <input type="date" name="tanggal_mutasi" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Desa Asal / Tujuan</label>
                    <input type="text" name="desa" class="form-control" placeholder="Kosongkan jika kelahiran/kematian">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-green"><i class="bi bi-save"></i> Simpan</button>
                <a href="mutasi.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>