<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Dashboard Admin';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

$total_penduduk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM penduduk WHERE status_kependudukan='aktif'"))['t'];
$total_kk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM kartu_keluarga"))['t'];
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pengajuan_surat WHERE status='menunggu'"))['t'];
$surat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM surat_terbit"))['t'];

$rt_data = mysqli_query($conn, "SELECT rt, COUNT(*) as total FROM penduduk WHERE status_kependudukan='aktif' GROUP BY rt ORDER BY rt");
$rt_labels = []; $rt_values = [];
while ($r = mysqli_fetch_assoc($rt_data)) {
    $rt_labels[] = 'RT ' . $r['rt'];
    $rt_values[] = $r['total'];
}

$jk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
    SUM(jenis_kelamin='L') as laki, 
    SUM(jenis_kelamin='P') as perempuan 
    FROM penduduk WHERE status_kependudukan='aktif'"));

$mutasi_labels = []; $mutasi_masuk = []; $mutasi_keluar = [];
for ($i = 5; $i >= 0; $i--) {
    $bln = date('m', strtotime("-$i months"));
    $thn = date('Y', strtotime("-$i months"));
    $nama_bulan = date('M Y', strtotime("-$i months"));
    $m = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM mutasi_masuk WHERE MONTH(tanggal_mutasi)='$bln' AND YEAR(tanggal_mutasi)='$thn'"))['t'];
    $k = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM mutasi_keluar WHERE MONTH(tanggal_mutasi)='$bln' AND YEAR(tanggal_mutasi)='$thn'"))['t'];
    $mutasi_labels[] = $nama_bulan;
    $mutasi_masuk[] = $m;
    $mutasi_keluar[] = $k;
}
?>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="mb-1 fw-bold">Dashboard</h2>
            <p class="text-muted mb-0">Selamat datang, <?= $_SESSION['nama_lengkap'] ?> 👋</p>
        </div>
        <div class="text-muted small">
            <i class="bi bi-calendar"></i> <?= tanggalIndo(date('Y-m-d')) ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card-stat p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-green me-3"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <h4 class="mb-0 fw-bold"><?= $total_penduduk ?></h4>
                        <small class="text-muted">Penduduk Aktif</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card-stat p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-blue me-3"><i class="bi bi-house-door-fill"></i></div>
                    <div>
                        <h4 class="mb-0 fw-bold"><?= $total_kk ?></h4>
                        <small class="text-muted">Kartu Keluarga</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card-stat p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-orange me-3"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <h4 class="mb-0 fw-bold"><?= $pending ?></h4>
                        <small class="text-muted">Pengajuan Pending</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card-stat p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-purple me-3"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                    <div>
                        <h4 class="mb-0 fw-bold"><?= $surat ?></h4>
                        <small class="text-muted">Surat Terbit</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card-stat p-4">
                <h5 class="fw-bold mb-3">Tren Mutasi Warga (6 Bulan Terakhir)</h5>
                <canvas id="chartMutasi" height="80"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-stat p-4">
                <h5 class="fw-bold mb-3">Komposisi Jenis Kelamin</h5>
                <canvas id="chartJK" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card-stat p-4">
                <h5 class="fw-bold mb-3">Penduduk per RT</h5>
                <canvas id="chartRT" height="150"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-stat p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Pengajuan Terbaru</h5>
                    <a href="pengajuan.php" class="btn btn-sm btn-green">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead><tr><th>Nama</th><th>Surat</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php
                        $q = "SELECT p.*, pd.nama_lengkap, js.nama_surat FROM pengajuan_surat p
                              JOIN penduduk pd ON p.penduduk_id=pd.id
                              JOIN jenis_surat js ON p.jenis_surat_id=js.id
                              ORDER BY p.tanggal_ajuan DESC LIMIT 5";
                        $r = mysqli_query($conn, $q);
                        if (mysqli_num_rows($r) == 0) {
                            echo "<tr><td colspan='3' class='text-center text-muted py-3'>Belum ada pengajuan</td></tr>";
                        }
                        while ($row = mysqli_fetch_assoc($r)):
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td><small><?= htmlspecialchars($row['nama_surat']) ?></small></td>
                                <td><span class="badge-status badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
new Chart(document.getElementById('chartMutasi'), {
    type: 'line',
    data: {
        labels: <?= json_encode($mutasi_labels) ?>,
        datasets: [
            { label: 'Mutasi Masuk', data: <?= json_encode($mutasi_masuk) ?>,
              borderColor: '#198754', backgroundColor: 'rgba(25,135,84,0.1)', tension: 0.4, fill: true },
            { label: 'Mutasi Keluar', data: <?= json_encode($mutasi_keluar) ?>,
              borderColor: '#dc3545', backgroundColor: 'rgba(220,53,69,0.1)', tension: 0.4, fill: true }
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('chartJK'), {
    type: 'doughnut',
    data: {
        labels: ['Laki-laki', 'Perempuan'],
        datasets: [{ data: [<?= $jk['laki'] ?? 0 ?>, <?= $jk['perempuan'] ?? 0 ?>],
                     backgroundColor: ['#0d6efd', '#fd7e14'] }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('chartRT'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($rt_labels) ?>,
        datasets: [{ label: 'Jumlah Penduduk', data: <?= json_encode($rt_values) ?>,
                     backgroundColor: '#198754', borderRadius: 8 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>

<?php require_once '../includes/footer.php'; ?>