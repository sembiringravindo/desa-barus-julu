<?php
// SETELAH DIPAKAI, HAPUS FILE INI!
$password = 'admin123';
echo "<h2>Generate Password Hash</h2>";
echo "<p>Password: <strong>$password</strong></p>";
echo "<p>Hash:</p>";
echo "<textarea style='width:100%;height:100px;'>" . password_hash($password, PASSWORD_DEFAULT) . "</textarea>";
echo "<p><strong>Copy hash di atas → </strong> phpMyAdmin → tabel <code>users</code> → edit <code>admin</code> → paste ke kolom <code>password</code> → Save.</p>";
echo "<p style='color:red;'><strong>Hapus file ini setelah selesai!</strong></p>";