<?php
require_once '../config/init.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect(BASE_URL . 'auth/login.php');
}

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM penduduk WHERE id=$id");
redirect('penduduk.php?msg=Data berhasil dihapus');