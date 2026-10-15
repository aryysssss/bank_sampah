<?php
require_once "../config/auth.php";
wajib_role(['admin']);
csrf_cek();

$id = (int)($_POST['id'] ?? 0);

try {
    $stmt = mysqli_prepare($koneksi, "DELETE FROM sampah WHERE id_sampah = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    flash('ok', 'Jenis sampah dihapus.');
} catch (mysqli_sql_exception $e) {
    flash('error', 'Tidak bisa dihapus: jenis sampah ini sudah dipakai di transaksi.');
}
pindah("sampah.php");
