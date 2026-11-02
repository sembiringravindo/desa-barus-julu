<aside class="sidebar">
    <div class="brand">
        <img src="<?= ASSETS_URL ?>img/logo.png" alt="Logo" class="logo-img">
        <div>
            <h5>Desa Barus Julu</h5>
            <small>Panel Warga</small>
        </div>
    </div>
    <a href="<?= BASE_URL ?>warga/dashboard.php" class="<?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>">
        <i class="bi bi-house"></i> Dashboard
    </a>
    <a href="<?= BASE_URL ?>warga/profil.php" class="<?= strpos($_SERVER['PHP_SELF'],'profil')!==false?'active':'' ?>">
        <i class="bi bi-person"></i> Profil Saya
    </a>
    <a href="<?= BASE_URL ?>warga/ajukan_surat.php" class="<?= strpos($_SERVER['PHP_SELF'],'ajukan')!==false?'active':'' ?>">
        <i class="bi bi-file-plus"></i> Ajukan Surat
    </a>
    <a href="<?= BASE_URL ?>warga/status_pengajuan.php" class="<?= strpos($_SERVER['PHP_SELF'],'status')!==false?'active':'' ?>">
        <i class="bi bi-clock-history"></i> Status Pengajuan
    </a>
    <a href="<?= BASE_URL ?>warga/surat_saya.php" class="<?= strpos($_SERVER['PHP_SELF'],'surat_saya')!==false?'active':'' ?>">
        <i class="bi bi-file-earmark-pdf"></i> Surat Saya
    </a>
    <hr style="border-color: rgba(255,255,255,0.15); margin: 12px 20px;">
    <a href="<?= BASE_URL ?>auth/logout.php">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</aside>