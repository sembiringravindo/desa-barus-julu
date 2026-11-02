<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'desa_barus_julu');
define('BASE_URL', 'http://localhost/desa-barus-julu/');
define('ASSETS_URL', BASE_URL . 'assets/');

date_default_timezone_set('Asia/Jakarta');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());
mysqli_set_charset($conn, 'utf8mb4');