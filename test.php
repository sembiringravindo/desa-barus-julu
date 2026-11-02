<?php
require_once 'config/session.php';

echo "<h1>Tes Sistem Desa Barus Julu</h1>";

// 1. Cek koneksi database
echo "<h3>1. Koneksi Database</h3>";
if ($conn) {
    echo "<p style='color:green;'>✅ Koneksi BERHASIL</p>";
    $tables = ['users','penduduk','kartu_keluarga','mutasi_masuk','mutasi_keluar','jenis_surat','pengajuan_surat','lampiran_pengajuan','tanda_tangan','surat_terbit'];
    echo "<p><strong>Jumlah tabel: </strong>" . count($tables) . "</p>";
    foreach ($tables as $t) {
        $r = mysqli_query($conn, "SHOW TABLES LIKE '$t'");
        echo mysqli_num_rows($r) ? "✅ $t<br>" : "❌ $t (tidak ada)<br>";
    }
} else {
    echo "<p style='color:red;'>❌ Koneksi GAGAL</p>";
}

// 2. Cek library composer
echo "<h3>2. Library Composer</h3>";
echo file_exists('vendor/autoload.php') ? "✅ Autoload ada<br>" : "❌ Autoload tidak ada — jalankan <code>composer install</code><br>";
echo file_exists('vendor/dompdf/dompdf') ? "✅ Dompdf ada<br>" : "❌ Dompdf tidak ada<br>";
echo file_exists('vendor/simplesoftwareio/simple-qrcode') ? "✅ Simple-QRCode ada<br>" : "❌ Simple-QRCode tidak ada<br>";

// 3. Cek folder uploads
echo "<h3>3. Folder Uploads</h3>";
$folders = ['assets/uploads/ktp','assets/uploads/foto','assets/uploads/surat','assets/uploads/ttd'];
foreach ($folders as $f) {
    echo is_dir($f) ? "✅ $f<br>" : "❌ $f (tidak ada)<br>";
}

// 4. Cek gambar
echo "<h3>4. Gambar Assets</h3>";
echo file_exists('assets/img/logo.png') ? "✅ logo.png<br>" : "⚠️ logo.png belum ada<br>";
echo file_exists('assets/img/desa.png') ? "✅ desa.png<br>" : "⚠️ desa.png belum ada<br>";

// 5. Cek admin
echo "<h3>5. Data Admin</h3>";
$adm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM users WHERE role='admin' LIMIT 1"));
echo $adm ? "✅ Admin terdaftar: <strong>{$adm['username']}</strong><br>" : "❌ Admin belum ada<br>";
echo "<p><strong>Info:</strong> Untuk update password admin, jalankan <code>generate_password.php</code>.</p>";

echo "<br><p style='color:#888;font-size:12px;'>Hapus file ini setelah selesai testing!</p>";