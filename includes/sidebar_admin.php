<aside class="sidebar">
    <div class="brand">
        <img src="<?= ASSETS_URL ?>img/logo.png" alt="Logo" class="logo-img">
        <div>
            <h5>Desa Barus Julu</h5>
            <small>Panel Admin</small>
        </div>
    </div>
    <a href="<?= BASE_URL ?>admin/dashboard.php" class="<?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="<?= BASE_URL ?>admin/penduduk.php" class="<?= strpos($_SERVER['PHP_SELF'],'penduduk')!==false?'active':'' ?>">
        <i class="bi bi-people"></i> Data Penduduk
    </a>
    <a href="<?= BASE_URL ?>admin/mutasi.php" class="<?= strpos($_SERVER['PHP_SELF'],'mutasi')!==false?'active':'' ?>">
        <i class="bi bi-arrow-left-right"></i> Mutasi Warga
    </a>
    <a href="<?= BASE_URL ?>admin/pengajuan.php" class="<?= strpos($_SERVER['PHP_SELF'],'pengajuan')!==false?'active':'' ?>">
        <i class="bi bi-file-earmark-text"></i> Pengajuan Surat    </a>
    <a href="<?= BASE_URL ?>admin/surat_terbit.php" class="<?= strpos($_SERVER['PHP_SELF'],'surat_terbit')!==false?'active':'' ?>">
        <i class="bi bi-file-earmark-pdf"></i> Surat Terbit
    </a>
    <a href="<?= BASE_URL ?>admin/laporan.php" class="<?= strpos($_SERVER['PHP_SELF'],'laporan')!==false?'active':'' ?>">
        <i class="bi bi-bar-chart"></i> Laporan
    </a>
    <a href="<?= BASE_URL ?>admin/ttd.php" class="<?= strpos($_SERVER['PHP_SELF'],'ttd')!==false?'active':'' ?>">
        <i class="bi bi-pen"></i> Tanda Tangan
    </a>
    <hr style="border-color: rgba(255,255,255,0.15); margin: 12px 20px;">
    <a href="<?= BASE_URL ?>auth/logout.php">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</aside>