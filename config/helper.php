<?php
function clean($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

function redirect($url) { header("Location: $url"); exit; }

function cekLogin() {
    if (!isset($_SESSION['user_id'])) redirect(BASE_URL . 'auth/login.php');
}

function cekRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role)
        redirect(BASE_URL . 'auth/login.php');
}

function generateNomorSurat($conn, $kode) {
    $tahun = date('Y'); $bulan = date('m');
    $q = "SELECT COUNT(*) as t FROM surat_terbit 
          WHERE MONTH(tanggal_terbit)='$bulan' AND YEAR(tanggal_terbit)='$tahun'";
    $r = mysqli_fetch_assoc(mysqli_query($conn, $q));
    $no = str_pad($r['t'] + 1, 3, '0', STR_PAD_LEFT);
    return "$kode/$no/DSU/$bulan/$tahun";
}

function generateHash($data) {
    return hash('sha256', $data . time() . rand(1000, 9999));
}

function tanggalIndo($tgl) {
    $b = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli',
          'Agustus','September','Oktober','November','Desember'];
    $p = explode('-', $tgl);
    return $p[2] . ' ' . $b[(int)$p[1]] . ' ' . $p[0];
}