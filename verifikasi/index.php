<?php
require_once '../config/session.php';

$hash = $_GET['hash'] ?? '';
$surat = null;
$status = 'invalid';

if ($hash) {
    $hash_clean = clean($conn, $hash);
    $surat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT st.*, js.nama_surat, pd.nama_lengkap
        FROM surat_terbit st
        JOIN pengajuan_surat p ON st.pengajuan_id = p.id
        JOIN penduduk pd ON p.penduduk_id = pd.id
        JOIN jenis_surat js ON p.jenis_surat_id = js.id
        WHERE st.hash_verifikasi = '$hash_clean'"));
    if ($surat) $status = 'valid';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat — Desa Barus Julu</title>
    <link rel="icon" href="<?= ASSETS_URL ?>img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-card" style="max-width: 600px;">
        <div class="login-logo">
            <img src="<?= ASSETS_URL ?>img/logo.png" alt="Logo" class="logo-img-lg">
            <h2>Verifikasi Surat Digital</h2>
            <p>Desa Barus Julu, Kec. Barusjahe, Kab. Karo</p>
        </div>
        
        <?php if (!$hash): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Masukkan kode verifikasi atau scan QR Code pada surat.
            </div>
            <form method="GET">
                <div class="input-group input-group-lg">
                    <input type="text" name="hash" class="form-control" placeholder="Masukkan kode verifikasi" required>
                    <button class="btn btn-green"><i class="bi bi-search"></i></button>
                </div>
            </form>
        <?php elseif ($status == 'valid'): ?>
            <div class="text-center mb-4">
                <i class="bi bi-patch-check-fill text-success" style="font-size: 4rem;"></i>
                <h4 class="fw-bold text-success mt-2">SURAT VALID / ASLI</h4>
                <p class="text-muted small">Surat ini terdaftar dalam sistem Desa Barus Julu</p>
            </div>
            <table class="table">
                <tr><th width="40%">Nomor Surat</th><td><strong><?= $surat['nomor_surat'] ?></strong></td></tr>
                <tr><th>Jenis Surat</th><td><?= htmlspecialchars($surat['nama_surat']) ?></td></tr>
                <tr><th>Nama Pemohon</th><td><?= htmlspecialchars($surat['nama_lengkap']) ?></td></tr>
                <tr><th>Tanggal Terbit</th><td><?= tanggalIndo($surat['tanggal_terbit']) ?></td></tr>
            </table>
        <?php else: ?>
            <div class="text-center">
                <i class="bi bi-x-octagon-fill text-danger" style="font-size: 4rem;"></i>
                <h4 class="fw-bold text-danger mt-2">SURAT TIDAK VALID</h4>
                <p class="text-muted">Kode verifikasi tidak ditemukan dalam sistem.</p>
            </div>
        <?php endif; ?>
        
        <p class="text-center mt-4 mb-0">
            <a href="<?= BASE_URL ?>" class="text-muted small text-decoration-none">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </p>
    </div>
</div>
</body>
</html>