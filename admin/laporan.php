<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Laporan';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

$jenis = $_GET['jenis'] ?? 'penduduk';
$tgl_awal = $_GET['tgl_awal'] ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
?>

<main class="main-content">
    <h2 class="fw-bold mb-4">Laporan</h2>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link <?= $jenis=='penduduk'?'active':'' ?>" href="?jenis=penduduk">Penduduk</a></li>
        <li class="nav-item"><a class="nav-link <?= $jenis=='mutasi'?'active':'' ?>" href="?jenis=mutasi">Mutasi</a></li>
        <li class="nav-item"><a class="nav-link <?= $jenis=='surat'?'active':'' ?>" href="?jenis=surat">Surat</a></li>
    </ul>

    <div class="card-stat p-4">
        <?php if ($jenis == 'penduduk'): ?>
            <?php
            $rt_data = mysqli_query($conn, "SELECT rt, 
                SUM(jenis_kelamin='L') as laki, 
                SUM(jenis_kelamin='P') as perempuan,
                COUNT(*) as total
                FROM penduduk WHERE status_kependudukan='aktif' GROUP BY rt");
            ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Laporan Penduduk per RT</h5>
                <a href="laporan_cetak.php?jenis=penduduk" target="_blank" class="btn btn-green">
                    <i class="bi bi-printer"></i> Cetak PDF
                </a>
            </div>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>RT</th><th>Laki-laki</th><th>Perempuan</th><th>Total</th></tr>
                </thead>
                <tbody>
                <?php 
                $total_l=0; $total_p=0; $total_all=0;
                while ($r = mysqli_fetch_assoc($rt_data)): 
                    $total_l += $r['laki']; $total_p += $r['perempuan']; $total_all += $r['total'];
                ?>
                    <tr>
                        <td>RT <?= $r['rt'] ?></td>
                        <td><?= $r['laki'] ?></td>
                        <td><?= $r['perempuan'] ?></td>
                        <td><strong><?= $r['total'] ?></strong></td>
                    </tr>
                <?php endwhile; ?>
                    <tr class="table-success fw-bold">
                        <td>TOTAL</td>
                        <td><?= $total_l ?></td>
                        <td><?= $total_p ?></td>
                        <td><?= $total_all ?></td>
                    </tr>
                </tbody>
            </table>

        <?php elseif ($jenis == 'mutasi'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Laporan Mutasi Warga</h5>
                <a href="laporan_cetak.php?jenis=mutasi&tgl_awal=<?= $tgl_awal ?>&tgl_akhir=<?= $tgl_akhir ?>" target="_blank" class="btn btn-green">
                    <i class="bi bi-printer"></i> Cetak PDF
                </a>
            </div>
            <form class="row g-2 mb-3">
                <input type="hidden" name="jenis" value="mutasi">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-green w-100"><i class="bi bi-filter"></i> Filter</button>
                </div>
            </form>

            <h6 class="fw-bold mt-3">Mutasi Masuk</h6>
            <table class="table table-sm table-hover">
                <thead class="table-light"><tr><th>Nama</th><th>Jenis</th><th>Tanggal</th><th>Asal</th></tr></thead>
                <tbody>
                <?php
                $q = "SELECT m.*, p.nama_lengkap FROM mutasi_masuk m
                      JOIN penduduk p ON m.penduduk_id=p.id
                      WHERE m.tanggal_mutasi BETWEEN '$tgl_awal' AND '$tgl_akhir'";
                $r = mysqli_query($conn, $q);
                while ($row = mysqli_fetch_assoc($r)):
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><?= ucwords(str_replace('_',' ',$row['jenis_mutasi'])) ?></td>
                        <td><?= tanggalIndo($row['tanggal_mutasi']) ?></td>
                        <td><?= htmlspecialchars($row['asal_desa'] ?? '-') ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

            <h6 class="fw-bold mt-4">Mutasi Keluar</h6>
            <table class="table table-sm table-hover">
                <thead class="table-light"><tr><th>Nama</th><th>Jenis</th><th>Tanggal</th><th>Tujuan</th></tr></thead>
                <tbody>
                <?php
                $q = "SELECT m.*, p.nama_lengkap FROM mutasi_keluar m
                      JOIN penduduk p ON m.penduduk_id=p.id
                      WHERE m.tanggal_mutasi BETWEEN '$tgl_awal' AND '$tgl_akhir'";
                $r = mysqli_query($conn, $q);
                while ($row = mysqli_fetch_assoc($r)):
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><?= ucwords(str_replace('_',' ',$row['jenis_mutasi'])) ?></td>
                        <td><?= tanggalIndo($row['tanggal_mutasi']) ?></td>
                        <td><?= htmlspecialchars($row['tujuan_desa'] ?? '-') ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Laporan Surat Terbit</h5>
                <a href="laporan_cetak.php?jenis=surat" target="_blank" class="btn btn-green">
                    <i class="bi bi-printer"></i> Cetak PDF
                </a>
            </div>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>No</th><th>Nomor Surat</th><th>Jenis</th><th>Nama</th><th>Tanggal</th></tr>
                </thead>
                <tbody>
                <?php
                $q = "SELECT st.*, js.nama_surat, pd.nama_lengkap FROM surat_terbit st
                      JOIN pengajuan_surat p ON st.pengajuan_id=p.id
                      JOIN penduduk pd ON p.penduduk_id=pd.id
                      JOIN jenis_surat js ON p.jenis_surat_id=js.id
                      ORDER BY st.tanggal_terbit DESC";
                $r = mysqli_query($conn, $q);
                $no=1;
                while ($row = mysqli_fetch_assoc($r)):
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nomor_surat'] ?></td>
                        <td><?= htmlspecialchars($row['nama_surat']) ?></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><?= tanggalIndo($row['tanggal_terbit']) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>