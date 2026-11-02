<?php
require_once '../config/init.php';
require_once '../vendor/autoload.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}

$id = (int)$_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT p.*, pd.*, js.nama_surat, js.kode_surat
    FROM pengajuan_surat p
    JOIN penduduk pd ON p.penduduk_id = pd.id
    JOIN jenis_surat js ON p.jenis_surat_id = js.id
    WHERE p.id = $id"));
if (!$data) redirect('pengajuan.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'];
    $catatan = clean($conn, $_POST['catatan'] ?? '');
    
    if ($aksi == 'disetujui') {
        $nomor = generateNomorSurat($conn, $data['kode_surat']);
        $hash = generateHash($nomor . $data['nik']);
        
        // Generate QR Code
        $qr_filename = 'qr_' . $id . '_' . time() . '.png';
        $qr_path = '../assets/uploads/surat/' . $qr_filename;
        $qr_content = BASE_URL . 'verifikasi/index.php?hash=' . $hash;
        
        // Cek apakah simple-qrcode ada
        if (class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode')) {
            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate($qr_content, $qr_path);
        }
        
        mysqli_query($conn, "UPDATE pengajuan_surat SET status='disetujui', catatan_admin='$catatan', tanggal_selesai=NOW() WHERE id=$id");
        
        $tanggal = date('Y-m-d');
        mysqli_query($conn, "INSERT INTO surat_terbit (pengajuan_id, nomor_surat, file_pdf, qr_code, hash_verifikasi, tanggal_terbit)
                             VALUES ($id, '$nomor', '', '$qr_filename', '$hash', '$tanggal')");
        
        redirect("pengajuan_detail.php?id=$id&msg=Pengajuan disetujui & surat berhasil dibuat");
    } elseif ($aksi == 'ditolak') {
        mysqli_query($conn, "UPDATE pengajuan_surat SET status='ditolak', catatan_admin='$catatan', tanggal_selesai=NOW() WHERE id=$id");
        redirect("pengajuan_detail.php?id=$id&msg=Pengajuan ditolak");
    }
}

$title = 'Detail Pengajuan';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

$lampiran = mysqli_query($conn, "SELECT * FROM lampiran_pengajuan WHERE pengajuan_id=$id");
?>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Detail Pengajuan</h2>
        <a href="pengajuan.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card-stat p-4 mb-3">
                <h5 class="fw-bold mb-3">Data Pemohon</h5>
                <table class="table table-borderless">
                    <tr><th width="200">Nama</th><td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
                    <tr><th>NIK</th><td><?= $data['nik'] ?></td></tr>
                    <tr><th>Alamat</th><td><?= htmlspecialchars($data['alamat']) ?> RT <?= $data['rt'] ?>/RW <?= $data['rw'] ?></td></tr>
                </table>
            </div>

            <div class="card-stat p-4 mb-3">
                <h5 class="fw-bold mb-3">Detail Pengajuan</h5>
                <table class="table table-borderless">
                    <tr><th width="200">Jenis Surat</th><td><?= htmlspecialchars($data['nama_surat']) ?></td></tr>
                    <tr><th>Keperluan</th><td><?= nl2br(htmlspecialchars($data['keperluan'])) ?></td></tr>
                    <tr><th>Tanggal Ajuan</th><td><?= date('d/m/Y H:i', strtotime($data['tanggal_ajuan'])) ?></td></tr>
                    <tr><th>Status</th><td><span class="badge-status badge-<?= $data['status'] ?>"><?= ucfirst($data['status']) ?></span></td></tr>
                </table>
            </div>

            <div class="card-stat p-4">
                <h5 class="fw-bold mb-3">Lampiran</h5>
                <?php if (mysqli_num_rows($lampiran) == 0): ?>
                    <p class="text-muted">Tidak ada lampiran</p>
                <?php else: ?>
                    <ul class="list-unstyled">
                    <?php while ($l = mysqli_fetch_assoc($lampiran)): ?>
                        <li class="mb-2">
                            <i class="bi bi-paperclip"></i>
                            <a href="<?= BASE_URL ?>assets/uploads/surat/<?= $l['path_file'] ?>" target="_blank">
                                <?= htmlspecialchars($l['nama_file']) ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-stat p-4">
                <h5 class="fw-bold mb-3">Aksi</h5>

                <?php if ($data['status'] == 'menunggu' || $data['status'] == 'diproses'): ?>
                    <form method="POST" class="mb-3">
                        <label class="form-label">Catatan Admin</label>
                        <textarea name="catatan" class="form-control mb-2" rows="3"></textarea>
                        <button name="aksi" value="disetujui" class="btn btn-green w-100 mb-2" onclick="return confirm('Setujui & generate surat?')">
                            <i class="bi bi-check-circle"></i> Setujui & Generate Surat
                        </button>
                        <button name="aksi" value="ditolak" class="btn btn-danger w-100" onclick="return confirm('Tolak pengajuan ini?')">
                            <i class="bi bi-x-circle"></i> Tolak
                        </button>
                    </form>
                <?php elseif ($data['status'] == 'disetujui'): ?>
                    <?php $surat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM surat_terbit WHERE pengajuan_id=$id")); ?>
                    <div class="alert alert-success small">
                        <strong>Surat Terbit:</strong><br>
                        No: <?= $surat['nomor_surat'] ?? '-' ?>
                    </div>
                    <a href="surat_terbit.php" class="btn btn-green w-100"><i class="bi bi-file-earmark-pdf"></i> Lihat Surat</a>
                <?php else: ?>
                    <div class="alert alert-danger small">Pengajuan ditolak</div>
                <?php endif; ?>

                <?php if ($data['catatan_admin']): ?>
                    <hr>
                    <p class="small mb-0"><strong>Catatan:</strong><br><?= nl2br(htmlspecialchars($data['catatan_admin'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>