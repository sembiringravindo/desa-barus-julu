<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'warga') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Ajukan Surat';
require_once '../includes/header.php';
require_once '../includes/sidebar_warga.php';

$user_id = $_SESSION['user_id'];
$penduduk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penduduk WHERE user_id=$user_id"));

$sukses = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $penduduk) {
    $jenis = (int)$_POST['jenis_surat_id'];
    $keperluan = clean($conn, $_POST['keperluan']);
    
    $q = "INSERT INTO pengajuan_surat (penduduk_id, jenis_surat_id, keperluan, status)
          VALUES ({$penduduk['id']}, $jenis, '$keperluan', 'menunggu')";
    if (mysqli_query($conn, $q)) {
        $pid = mysqli_insert_id($conn);
        
        if (!empty($_FILES['lampiran']['name'][0])) {
            foreach ($_FILES['lampiran']['name'] as $i => $nama) {
                if ($_FILES['lampiran']['error'][$i] == 0) {
                    $ext = pathinfo($nama, PATHINFO_EXTENSION);
                    $new = 'lampiran_' . $pid . '_' . time() . '_' . $i . '.' . $ext;
                    $path = '../assets/uploads/surat/' . $new;
                    if (move_uploaded_file($_FILES['lampiran']['tmp_name'][$i], $path)) {
                        mysqli_query($conn, "INSERT INTO lampiran_pengajuan (pengajuan_id, nama_file, path_file)
                                            VALUES ($pid, '$nama', '$new')");
                    }
                }
            }
        }
        $sukses = 'Pengajuan berhasil dikirim!';
    } else $error = 'Gagal: ' . mysqli_error($conn);
}

$jenis_list = mysqli_query($conn, "SELECT * FROM jenis_surat WHERE aktif=1 ORDER BY nama_surat");
?>

<main class="main-content">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Ajukan Surat</h2>
        <p class="text-muted mb-0">Isi form di bawah untuk mengajukan permohonan surat</p>
    </div>

    <?php if (!$penduduk): ?>
        <div class="alert alert-warning">Data penduduk Anda belum terdaftar. Hubungi perangkat desa.</div>
    <?php else: ?>
        <?php if ($sukses): ?><div class="alert alert-success"><?= $sukses ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

        <div class="card-stat p-4">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Jenis Surat *</label>
                    <select name="jenis_surat_id" class="form-select" id="jenisSurat" required>
                        <option value="">-- Pilih Jenis Surat --</option>
                        <?php while ($j = mysqli_fetch_assoc($jenis_list)): ?>
                            <option value="<?= $j['id'] ?>" data-syarat="<?= htmlspecialchars($j['syarat']) ?>">
                                <?= htmlspecialchars($j['nama_surat']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="alert alert-info" id="infoSyarat" style="display:none;">
                    <strong><i class="bi bi-info-circle"></i> Syarat:</strong>
                    <span id="textSyarat"></span>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keperluan *</label>
                    <textarea name="keperluan" class="form-control" rows="3" required 
                              placeholder="Contoh: Untuk keperluan pendaftaran sekolah"></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Upload Berkas Persyaratan</label>
                    <input type="file" name="lampiran[]" class="form-control" multiple accept="image/*,.pdf">
                    <small class="text-muted">Format: JPG, PNG, PDF. Bisa pilih lebih dari satu file.</small>
                </div>

                <button type="submit" class="btn btn-green">
                    <i class="bi bi-send"></i> Kirim Pengajuan
                </button>
            </form>
        </div>
    <?php endif; ?>
</main>

<script>
document.getElementById('jenisSurat')?.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const syarat = opt.dataset.syarat;
    const info = document.getElementById('infoSyarat');
    if (syarat) {
        document.getElementById('textSyarat').textContent = syarat;
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>