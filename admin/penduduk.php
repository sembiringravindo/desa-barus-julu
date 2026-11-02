<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Data Penduduk';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

$search = $_GET['search'] ?? '';
$filter_rt = $_GET['rt'] ?? '';
$filter_status = $_GET['status'] ?? 'aktif';

$where = ["1=1"];
if ($search) $where[] = "(p.nama_lengkap LIKE '%$search%' OR p.nik LIKE '%$search%' OR p.no_kk LIKE '%$search%')";
if ($filter_rt) $where[] = "p.rt = '$filter_rt'";
if ($filter_status) $where[] = "p.status_kependudukan = '$filter_status'";
$where_sql = implode(' AND ', $where);

$query = "SELECT p.*, k.kepala_keluarga FROM penduduk p
          LEFT JOIN kartu_keluarga k ON p.no_kk = k.no_kk
          WHERE $where_sql ORDER BY p.nama_lengkap ASC";
$result = mysqli_query($conn, $query);

$list_rt = mysqli_query($conn, "SELECT DISTINCT rt FROM penduduk ORDER BY rt");
?>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">Data Penduduk</h2>
            <p class="text-muted mb-0">Kelola data penduduk Desa Barus Julu</p>
        </div>
        <a href="penduduk_tambah.php" class="btn btn-green">
            <i class="bi bi-plus-circle"></i> Tambah Penduduk
        </a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="card-stat p-3 mb-3">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, NIK, atau No. KK..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <select name="rt" class="form-select">
                    <option value="">Semua RT</option>
                    <?php while ($rt = mysqli_fetch_assoc($list_rt)): ?>
                        <option value="<?= $rt['rt'] ?>" <?= $filter_rt == $rt['rt'] ? 'selected' : '' ?>>RT <?= $rt['rt'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="aktif" <?= $filter_status=='aktif'?'selected':'' ?>>Aktif</option>
                    <option value="pindah" <?= $filter_status=='pindah'?'selected':'' ?>>Pindah</option>
                    <option value="meninggal" <?= $filter_status=='meninggal'?'selected':'' ?>>Meninggal</option>
                    <option value="" <?= $filter_status==''?'selected':'' ?>>Semua</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-green w-100"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>
    </div>

    <div class="card-stat p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th><th>NIK</th><th>Nama Lengkap</th><th>JK</th>
                        <th>Alamat</th><th>Status</th><th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; if (mysqli_num_rows($result) == 0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data</td></tr>
                <?php else: while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><small><?= $row['nik'] ?></small></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><?= $row['jenis_kelamin'] ?></td>
                        <td><small><?= htmlspecialchars($row['alamat']) ?> RT <?= $row['rt'] ?>/RW <?= $row['rw'] ?></small></td>
                        <td>
                            <?php $color = $row['status_kependudukan']=='aktif'?'success':($row['status_kependudukan']=='pindah'?'warning':'secondary'); ?>
                            <span class="badge bg-<?= $color ?>"><?= ucfirst($row['status_kependudukan']) ?></span>
                        </td>
                        <td class="text-end">
                            <a href="penduduk_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="penduduk_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <a href="javascript:void(0)" onclick="konfirmasiHapus('penduduk_hapus.php?id=<?= $row['id'] ?>')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>