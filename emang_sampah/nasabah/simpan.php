<?php
require_once "../config/auth.php";
wajib_role(['nasabah']);
csrf_cek();

$id_user   = (int)$_SESSION['user']['id_user'];
$id_sampah = (int)($_POST['id_sampah'] ?? 0);
$berat_kg  = $_POST['berat_kg'] ?? '';

if ($id_sampah <= 0 || !is_numeric($berat_kg) || $berat_kg <= 0) {
    flash('error', 'Data setoran tidak valid!');
    pindah("setor.php");
}
$berat_kg = round((float)$berat_kg, 2);
if ($berat_kg <= 0) {
    flash('error', 'Berat minimal 0,01 kg.');
    pindah("setor.php");
}

$stmt = mysqli_prepare($koneksi, "SELECT harga_setor_per_kg FROM sampah WHERE id_sampah = ?");
mysqli_stmt_bind_param($stmt, "i", $id_sampah);
mysqli_stmt_execute($stmt);
$sampah = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$sampah) {
    flash('error', 'Jenis sampah tidak ditemukan!');
    pindah("setor.php");
}

$total_rp = round($sampah['harga_setor_per_kg'] * $berat_kg, 2);

$stmt = mysqli_prepare($koneksi,
    "INSERT INTO setoran (id_user, id_sampah, berat_kg, total_rp) VALUES (?,?,?,?)");
mysqli_stmt_bind_param($stmt, "iidd", $id_user, $id_sampah, $berat_kg, $total_rp);
mysqli_stmt_execute($stmt);

flash('ok', 'Setoran terkirim, menunggu persetujuan admin.');
pindah("riwayat.php");
