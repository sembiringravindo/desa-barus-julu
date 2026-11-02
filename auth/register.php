<?php
require_once '../config/session.php';

$error = ''; $sukses = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = clean($conn, $_POST['nama_lengkap']);
    $email = clean($conn, $_POST['email']);
    $no_hp = clean($conn, $_POST['no_hp']);
    
    $cek = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) $error = 'Username sudah terdaftar!';
    else {
        $q = "INSERT INTO users (username, password, nama_lengkap, email, no_hp, role, status)
              VALUES ('$username', '$password', '$nama', '$email', '$no_hp', 'warga', 'pending')";
        if (mysqli_query($conn, $q)) $sukses = 'Pendaftaran berhasil! Menunggu verifikasi admin.';
        else $error = 'Gagal: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Warga — Desa Barus Julu</title>
    <link rel="icon" href="<?= ASSETS_URL ?>img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-card" style="max-width: 540px;">
        <div class="login-logo">
            <img src="<?= ASSETS_URL ?>img/logo.png" alt="Logo Desa" class="logo-img-lg">
            <h2>Registrasi Warga</h2>
            <p>Daftar akun untuk mengakses layanan desa</p>
        </div>
        
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <?php if ($sukses): ?><div class="alert alert-success"><?= $sukses ?></div><?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold small">NAMA LENGKAP</label>
                <input type="text" name="nama_lengkap" class="form-control form-control-lg" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">USERNAME / NIK</label>
                <input type="text" name="username" class="form-control form-control-lg" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold small">EMAIL</label>
                    <input type="email" name="email" class="form-control form-control-lg">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold small">NO. HP</label>
                    <input type="text" name="no_hp" class="form-control form-control-lg">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold small">PASSWORD</label>
                <input type="password" name="password" class="form-control form-control-lg" required minlength="6">
                <small class="text-muted">Minimal 6 karakter</small>
            </div>
            <button type="submit" class="btn btn-green w-100 btn-lg">
                <i class="bi bi-person-plus"></i> Daftar
            </button>
        </form>
        
        <p class="text-center mt-4 mb-0 small">
            Sudah punya akun? <a href="login.php" class="fw-semibold text-decoration-none" style="color: var(--primary);">Login</a>
        </p>
    </div>
</div>
</body>
</html>