<?php
require_once 'config/session.php';

$total_penduduk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM penduduk WHERE status_kependudukan='aktif'"))['t'];
$total_surat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM jenis_surat WHERE aktif=1"))['t'];
$total_kk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM kartu_keluarga"))['t'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Barus Julu — Layanan Digital Desa</title>
    <meta name="description" content="Sistem Informasi Pendataan Penduduk & Mutasi Warga Desa Barus Julu, Kec. Barusjahe, Kab. Karo">
    
    <link rel="icon" type="image/png" href="<?= ASSETS_URL ?>img/logo.png">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= ASSETS_URL ?>css/style.css">
</head>
<body>

<!-- ==================== NAVBAR ==================== -->
<nav class="landing-nav">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">
                <img src="<?= ASSETS_URL ?>img/logo.png" alt="Logo Desa" class="logo-img">
                <div>
                    <div style="line-height: 1.1;">Desa Barus Julu</div>
                    <small style="font-size: 0.7rem; color: #64748b; font-weight: 500;">Kec. Barusjahe, Karo</small>
                </div>
            </a>
            
            <div class="d-none d-lg-flex align-items-center gap-2">
                <a href="#fitur" class="nav-link">Fitur</a>
                <a href="#alur" class="nav-link">Alur</a>
                <a href="#tentang" class="nav-link">Tentang</a>
                <a href="<?= BASE_URL ?>auth/login.php" class="btn btn-green ms-2">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            </div>
            
            <a href="<?= BASE_URL ?>auth/login.php" class="btn btn-green d-lg-none btn-sm">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
        </div>
    </div>
</nav>

<!-- ==================== HERO ==================== -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7" data-aos="fade-right">
                <div class="hero-badge">
                    <i class="bi bi-stars"></i> Desa Digital 2026
                </div>
                <h1>
                    Sistem Informasi<br>
                    <span class="highlight">Desa Barus Julu</span>
                </h1>
                <p>
                    Layanan administrasi desa yang <strong>cepat, mudah, dan transparan</strong>. 
                    Ajukan surat online, pantau status pengajuan, dan dapatkan surat digital 
                    bertanda tangan elektronik — tanpa perlu datang ke kantor desa.
                </p>
                <div>
                    <a href="<?= BASE_URL ?>auth/register.php" class="btn-hero btn-hero-primary">
                        <i class="bi bi-person-plus-fill"></i> Daftar Sekarang
                    </a>
                    <a href="#fitur" class="btn-hero btn-hero-outline">
                        <i class="bi bi-play-circle"></i> Pelajari Fitur
                    </a>
                </div>
                
                <div class="hero-stats">
                    <div class="hero-stat">
                        <h3><?= number_format($total_penduduk) ?>+</h3>
                        <p>Penduduk Terdata</p>
                    </div>
                    <div class="hero-stat">
                        <h3><?= $total_surat ?></h3>
                        <p>Jenis Surat Layanan</p>
                    </div>
                    <div class="hero-stat">
                        <h3>24/7</h3>
                        <p>Akses Online</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left">
                <div style="text-align: center; position: relative;">
                    <img src="<?= ASSETS_URL ?>img/logo.png" alt="Logo Desa Barus Julu" 
                         style="width: 320px; height: 320px; object-fit: contain; 
                                filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3));
                                animation: float 6s ease-in-out infinite;">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}
</style>

<!-- ==================== FITUR ==================== -->
<section class="section" id="fitur" style="background: white;">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <div class="badge-section">FITUR UNGGULAN</div>
            <h2>Semua Layanan Desa dalam Satu Platform</h2>
            <p>Akses layanan administrasi desa kapan saja, di mana saja — cukup dari smartphone Anda</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-file-earmark-plus"></i></div>
                    <h5>Ajukan Surat Online</h5>
                    <p>Ajukan permohonan surat kapan saja dan di mana saja tanpa perlu antre di kantor desa.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-qr-code"></i></div>
                    <h5>Tanda Tangan Elektronik</h5>
                    <p>Surat digital dilengkapi tanda tangan elektronik dan QR Code verifikasi keaslian.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                    <h5>Unduh PDF Resmi</h5>
                    <p>Surat yang telah disetujui dapat langsung diunduh dalam format PDF resmi desa.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-search"></i></div>
                    <h5>Lacak Status Real-Time</h5>
                    <p>Pantau status pengajuan surat Anda secara real-time: menunggu, diproses, atau selesai.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                    <h5>Data Aman & Terpusat</h5>
                    <p>Data kependudukan tersimpan aman dalam basis data terpusat dengan pencadangan berkala.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-phone"></i></div>
                    <h5>Responsif Mobile</h5>
                    <p>Akses layanan desa dari smartphone, tablet, atau komputer dengan tampilan optimal.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== ALUR ==================== -->
<section class="section" id="alur">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <div class="badge-section">CARA KERJA</div>
            <h2>4 Langkah Mudah Ajukan Surat</h2>
            <p>Proses yang simpel dan cepat untuk mendapatkan surat resmi desa</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3" data-aos="fade-up">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h6>Daftar Akun</h6>
                    <p>Buat akun dengan NIK dan data diri Anda.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h6>Pilih Jenis Surat</h6>
                    <p>Pilih jenis surat dan isi keperluan pengajuan.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h6>Upload Berkas</h6>
                    <p>Unggah dokumen persyaratan (KTP, KK, dll).</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h6>Unduh Surat</h6>
                    <p>Surat PDF siap diunduh setelah disetujui admin.</p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?= BASE_URL ?>auth/register.php" class="btn btn-green btn-lg px-4">
                <i class="bi bi-rocket-takeoff"></i> Mulai Sekarang
            </a>
        </div>
    </div>
</section>

<!-- ==================== CTA ==================== -->
<section class="cta-section" id="tentang">
    <div class="container">
        <div data-aos="fade-up">
            <h2>Siap Melayani Warga Desa Barus Julu</h2>
            <p>Layanan administrasi desa kini lebih mudah, cepat, dan transparan.</p>
            <a href="<?= BASE_URL ?>auth/register.php" class="btn btn-gold btn-lg px-4 py-3">
                <i class="bi bi-person-plus-fill"></i> Daftar Sebagai Warga
            </a>
        </div>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer class="footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="footer-brand">
                    <img src="<?= ASSETS_URL ?>img/logo.png" alt="Logo" class="logo-img">
                    <h5>Desa Barus Julu</h5>
                </div>
                <p>Kecamatan Barusjahe, Kabupaten Karo,<br>Provinsi Sumatera Utara, 22172</p>
                <p>
                    <i class="bi bi-telephone-fill"></i> (0628) XXXXXX<br>
                    <i class="bi bi-envelope-fill"></i> desabarusjulu@karo.go.id
                </p>
            </div>
            <div class="col-lg-2 col-md-4">
                <h6>Menu</h6>
                <a href="#fitur">Fitur</a>
                <a href="#alur">Alur</a>
                <a href="#tentang">Tentang</a>
                <a href="<?= BASE_URL ?>auth/login.php">Login</a>
            </div>
            <div class="col-lg-3 col-md-4">
                <h6>Layanan Surat</h6>
                <a href="#">Surat Keterangan Domisili</a>
                <a href="#">Surat Keterangan Usaha</a>
                <a href="#">Surat Keterangan Tidak Mampu</a>
                <a href="#">Surat Pengantar Pindah</a>
            </div>
            <div class="col-lg-3 col-md-4">
                <h6>Jam Pelayanan</h6>
                <p class="mb-0">
                    <strong>Senin – Jumat</strong><br>
                    08.00 – 16.30 WIB
                </p>
            </div>
        </div>
        <div class="copyright">
            &copy; <?= date('Y') ?> Desa Barus Julu. Dibuat dengan ❤️ untuk warga.
        </div>
    </div>
</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 800, once: true, offset: 80 });
</script>
<script src="<?= ASSETS_URL ?>js/script.js"></script>
</body>
</html>