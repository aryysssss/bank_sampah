<?php
require_once "../config/auth.php";
wajib_role(['admin']);
csrf_cek();

$id = (int)($_POST['id'] ?? 0);

if ($id === (int)$_SESSION['user']['id_user']) {
    flash('error', 'Tidak bisa menghapus akun sendiri!');
    pindah("users.php");
}

try {
    $stmt = mysqli_prepare($koneksi, "DELETE FROM users WHERE id_user = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    flash('ok', 'Akun dihapus.');
} catch (mysqli_sql_exception $e) {
    flash('error', 'Tidak bisa dihapus: akun ini sudah memiliki riwayat transaksi.');
}
pindah("users.php");
