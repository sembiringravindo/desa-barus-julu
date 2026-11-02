<?php
require_once '../config/init.php';
require_once '../vendor/autoload.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}

use Dompdf\Dompdf;

$jenis = $_GET['jenis'] ?? 'penduduk';
$tgl_awal = $_GET['tgl_awal'] ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

$judul = "Laporan " . ucfirst($jenis);
$isi = '';

if ($jenis == 'penduduk') {
    $r = mysqli_query($conn, "SELECT rt, SUM(jenis_kelamin='L') as laki, SUM(jenis_kelamin='P') as perempuan, COUNT(*) as total
                              FROM penduduk WHERE status_kependudukan='aktif' GROUP BY rt");
    $isi = '<table border="1" cellpadding="6" cellspacing="0" width="100%">
            <tr style="background:#198754;color:white;"><th>RT</th><th>Laki-laki</th><th>Perempuan</th><th>Total</th></tr>';
    $tl=0;$tp=0;$tt=0;
    while ($row = mysqli_fetch_assoc($r)) {
        $isi .= "<tr><td>RT {$row['rt']}</td><td>{$row['laki']}</td><td>{$row['perempuan']}</td><td>{$row['total']}</td></tr>";
        $tl+=$row['laki']; $tp+=$row['perempuan']; $tt+=$row['total'];
    }
    $isi .= "<tr style='font-weight:bold;background:#d1e7dd;'><td>TOTAL</td><td>$tl</td><td>$tp</td><td>$tt</td></tr></table>";
} else {
    $isi = '<p>Laporan tersedia di sistem.</p>';
}

$html = "
<html><head><style>body{font-family:Arial,sans-serif;font-size:11pt;}
h2{text-align:center;margin-bottom:5px;} p{text-align:center;margin:0;}</style></head>
<body>
<h2>PEMERINTAH KABUPATEN KARO</h2>
<h2>DESA BARUS JULU</h2>
<p>Kecamatan Barusjahe, Kabupaten Karo, Sumatera Utara 22172</p>
<hr>
<h3 style='text-align:center;'>$judul</h3>
$isi
<p style='text-align:right;margin-top:40px;'>Barus Julu, " . date('d F Y') . "</p>
<p style='text-align:right;'>Kepala Desa Barus Julu</p>
<p style='text-align:right;margin-top:60px;font-weight:bold;'>(............................)</p>
</body></html>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Laporan_' . ucfirst($jenis) . '.pdf', ['Attachment' => false]);