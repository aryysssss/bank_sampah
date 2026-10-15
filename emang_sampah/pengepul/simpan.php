<?php
require_once "../config/layout.php";
wajib_role(['pengepul']);
csrf_cek();

$id_pengepul = (int)$_SESSION['user']['id_user'];
$id_sampah   = (int)($_POST['id_sampah'] ?? 0);
$berat_kg    = $_POST['berat_kg'] ?? '';

if ($id_sampah <= 0 || !is_numeric($berat_kg) || $berat_kg <= 0) {
    flash('error', 'Data tidak valid!');
    pindah("beli.php");
}
$berat_kg = round((float)$berat_kg, 2);
if ($berat_kg <= 0) {
    flash('error', 'Berat minimal 0,01 kg.');
    pindah("beli.php");
}

mysqli_begin_transaction($koneksi);
try {
    $stmt = mysqli_prepare($koneksi,
        "SELECT harga_jual_per_kg, stok_kg FROM sampah WHERE id_sampah = ? FOR UPDATE");
    mysqli_stmt_bind_param($stmt, "i", $id_sampah);
    mysqli_stmt_execute($stmt);
    $sampah = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$sampah) throw new Exception('Jenis sampah tidak ditemukan!');
    if ($berat_kg > (float)$sampah['stok_kg']) {
        throw new Exception('Stok tidak cukup! Stok tersedia: ' . kg($sampah['stok_kg']));
    }

    $total_rp = round($sampah['harga_jual_per_kg'] * $berat_kg, 2);

    // kunci baris pengepul lalu cek saldo
    $s = mysqli_prepare($koneksi, "SELECT saldo FROM users WHERE id_user = ? FOR UPDATE");
    mysqli_stmt_bind_param($s, "i", $id_pengepul);
    mysqli_stmt_execute($s);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($s));

    if ((float)$user['saldo'] < $total_rp) {
        throw new Exception('Saldo tidak cukup! Saldo Anda ' . rp($user['saldo']) . ', total pembelian ' . rp($total_rp) . '.');
    }

    // saldo langsung dipotong; dikembalikan kalau admin menolak
    $u = mysqli_prepare($koneksi, "UPDATE users SET saldo = saldo - ? WHERE id_user = ?");
    mysqli_stmt_bind_param($u, "di", $total_rp, $id_pengepul);
    mysqli_stmt_execute($u);

    $stmt = mysqli_prepare($koneksi,
        "INSERT INTO pembelian (id_pengepul, id_sampah, berat_kg, total_rp) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "iidd", $id_pengepul, $id_sampah, $berat_kg, $total_rp);
    mysqli_stmt_execute($stmt);

    mysqli_commit($koneksi);
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    flash('error', $e->getMessage());
    pindah("beli.php");
}

flash('ok', 'Pembelian diajukan, saldo dipotong ' . rp($total_rp) . '. Menunggu persetujuan admin.');
pindah("riwayat.php");
