<?php
require_once '../config/session.php';

if (isset($_SESSION['user_id'])) {
    redirect($_SESSION['role'] === 'admin' 
        ? BASE_URL . 'admin/dashboard.php' 
        : BASE_URL . 'warga/dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($conn, $_POST['username']);
    $password = $_POST['password'];
    
    $q = "SELECT * FROM users WHERE username = '$username'";
    $r = mysqli_query($conn, $q);
    
    if (mysqli_num_rows($r) === 1) {
        $u = mysqli_fetch_assoc($r);
        if (password_verify($password, $u['password'])) {
            if ($u['status'] !== 'aktif') $error = 'Akun Anda belum diaktifkan oleh admin.';
            else {
                $_SESSION['user_id'] = $u['id'];
                $_SESSION['username'] = $u['username'];
                $_SESSION['nama_lengkap'] = $u['nama_lengkap'];
                $_SESSION['role'] = $u['role'];
                redirect($u['role'] === 'admin' 
                    ? BASE_URL . 'admin/dashboard.php' 
                    : BASE_URL . 'warga/dashboard.php');
            }
        } else $error = 'Password salah!';
    } else $error = 'Username tidak ditemukan!';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Desa Barus Julu</title>
    <link rel="icon" href="<?= ASSETS_URL ?>img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-logo">
            <img src="<?= ASSETS_URL ?>img/logo.png" alt="Logo Desa" class="logo-img-lg">
            <h2>Desa Barus Julu</h2>
            <p>Sistem Informasi Pendataan Penduduk</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> <?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold small">USERNAME / NIK</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control form-control-lg" 
                           placeholder="Masukkan username atau NIK" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold small">PASSWORD</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control form-control-lg" 
                           placeholder="Masukkan password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-green w-100 btn-lg">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
        </form>
        
        <p class="text-center mt-4 mb-0 small">
            Belum punya akun? <a href="register.php" class="fw-semibold text-decoration-none" style="color: var(--primary);">Daftar Sekarang</a>
        </p>
        <p class="text-center mt-3 mb-0">
            <a href="<?= BASE_URL ?>" class="text-muted small text-decoration-none">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </p>
    </div>
</div>
</body>
</html>