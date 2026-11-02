<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}
$title = 'Tanda Tangan';
require_once '../includes/header.php';
require_once '../includes/sidebar_admin.php';

$sukses = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jabatan = clean($conn, $_POST['jabatan']);
    if (!empty($_FILES['file_ttd']['name'])) {
        $ext = pathinfo($_FILES['file_ttd']['name'], PATHINFO_EXTENSION);
        $new = 'ttd_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $path = '../assets/uploads/ttd/' . $new;
        if (move_uploaded_file($_FILES['file_ttd']['tmp_name'], $path)) {
            mysqli_query($conn, "UPDATE tanda_tangan SET aktif=0 WHERE user_id={$_SESSION['user_id']}");
            mysqli_query($conn, "INSERT INTO tanda_tangan (user_id, file_ttd, jabatan) VALUES ({$_SESSION['user_id']}, '$new', '$jabatan')");
            $sukses = 'Tanda tangan berhasil diupload';
        }
    }
}

$list = mysqli_query($conn, "SELECT t.*, u.nama_lengkap FROM tanda_tangan t
    JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC");
?>

<main class="main-content">
    <h2 class="fw-bold mb-4">Kelola Tanda Tangan Elektronik</h2>

    <?php if ($sukses): ?><div class="alert alert-success"><?= $sukses ?></div><?php endif; ?>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card-stat p-4">
                <h5 class="fw-bold mb-3">Upload TTD Baru</h5>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Jabatan *</label>
                        <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Kepala Desa Barus Julu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File TTD (PNG transparan) *</label>
                        <input type="file" name="file_ttd" class="form-control" accept="image/*" required>
                        <small class="text-muted">Format PNG dengan background transparan lebih baik</small>
                    </div>
                    <button type="submit" class="btn btn-green w-100"><i class="bi bi-upload"></i> Upload</button>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card-stat p-4">
                <h5 class="fw-bold mb-3">Riwayat Tanda Tangan</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Jabatan</th><th>Pemilik</th><th>TTD</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($list)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['jabatan']) ?></td>
                                <td><small><?= htmlspecialchars($row['nama_lengkap']) ?></small></td>
                                <td><img src="<?= ASSETS_URL ?>uploads/ttd/<?= $row['file_ttd'] ?>" style="max-height:50px;"></td>
                                <td><?= $row['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>