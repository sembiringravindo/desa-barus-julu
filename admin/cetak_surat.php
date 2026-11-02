<?php
require_once '../config/init.php';
require_once '../vendor/autoload.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}

use Dompdf\Dompdf;

$id = (int)$_GET['id'];
$surat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT st.*, p.keperluan, pd.*, js.nama_surat, js.kode_surat
    FROM surat_terbit st
    JOIN pengajuan_surat p ON st.pengajuan_id = p.id
    JOIN penduduk pd ON p.penduduk_id = pd.id
    JOIN jenis_surat js ON p.jenis_surat_id = js.id
    WHERE st.id = $id"));
if (!$surat) die('Surat tidak ditemukan');

$ttd = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tanda_tangan WHERE aktif=1 ORDER BY id DESC LIMIT 1"));
$qr_file = '../assets/uploads/surat/' . $surat['qr_code'];
$qr_base64 = file_exists($qr_file) ? 'data:image/png;base64,' . base64_encode(file_get_contents($qr_file)) : '';
$ttd_base64 = ($ttd && file_exists('../assets/uploads/ttd/' . $ttd['file_ttd'])) 
    ? 'data:image/png;base64,' . base64_encode(file_get_contents('../assets/uploads/ttd/' . $ttd['file_ttd'])) 
    : '';

$html = '
<html><head><style>
body{font-family:"Times New Roman",serif;font-size:12pt;line-height:1.5;}
.kop{text-align:center;border-bottom:3px double #000;padding-bottom:10px;margin-bottom:20px;}
.kop h2{margin:0;font-size:16pt;}.kop h3{margin:3px 0;font-size:14pt;}
.kop p{margin:0;font-size:10pt;}
.judul{text-align:center;font-weight:bold;text-decoration:underline;font-size:14pt;margin:20px 0;}
table.data{margin:15px 0 15px 40px;}table.data td{padding:3px 8px;vertical-align:top;}
.ttd{margin-top:40px;text-align:right;}.ttd .nama{margin-top:60px;font-weight:bold;text-decoration:underline;}
.qr{text-align:right;margin-top:20px;}.qr img{width:100px;}
</style></head><body>
<div class="kop">
    <h2>PEMERINTAH KABUPATEN KARO</h2>
    <h3>KECAMATAN BARUSJAHE</h3>
    <h3 style="font-size:16pt;">DESA BARUS JULU</h3>
    <p>Kode Pos 22172</p>
</div>
<div class="judul">' . strtoupper($surat['nama_surat']) . '</div>
<p style="text-align:center;">Nomor: ' . $surat['nomor_surat'] . '</p>
<p>Yang bertanda tangan di bawah ini Kepala Desa Barus Julu, Kecamatan Barusjahe, Kabupaten Karo, dengan ini menerangkan bahwa:</p>
<table class="data">
<tr><td>Nama</td><td>:</td><td><strong>' . htmlspecialchars($surat['nama_lengkap']) . '</strong></td></tr>
<tr><td>NIK</td><td>:</td><td>' . $surat['nik'] . '</td></tr>
<tr><td>Tempat/Tgl Lahir</td><td>:</td><td>' . $surat['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($surat['tanggal_lahir'])) . '</td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td>' . ($surat['jenis_kelamin']=='L'?'Laki-laki':'Perempuan') . '</td></tr>
<tr><td>Agama</td><td>:</td><td>' . $surat['agama'] . '</td></tr>
<tr><td>Pekerjaan</td><td>:</td><td>' . $surat['pekerjaan'] . '</td></tr>
<tr><td>Alamat</td><td>:</td><td>' . $surat['alamat'] . ' RT ' . $surat['rt'] . '/RW ' . $surat['rw'] . '</td></tr>
</table>
<p>Bahwa nama tersebut di atas adalah benar-benar warga Desa Barus Julu dan yang bersangkutan mengajukan surat ini untuk keperluan: <strong>' . htmlspecialchars($surat['keperluan']) . '</strong>.</p>
<p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
<div class="qr"><img src="' . $qr_base64 . '"><br><small>Scan untuk verifikasi keaslian</small></div>
<div class="ttd">
Barus Julu, ' . tanggalIndo($surat['tanggal_terbit']) . '<br>
Kepala Desa Barus Julu<br>
' . ($ttd_base64 ? '<img src="' . $ttd_base64 . '" style="height:80px;">' : '<br><br><br>') . '
<div class="nama">' . ($ttd['jabatan'] ?? '(............................)') . '</div>
</div>
</body></html>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Surat_' . $surat['nomor_surat'] . '.pdf', ['Attachment' => false]);