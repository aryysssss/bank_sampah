<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Akses tidak valid.");
}

$nis      = $_POST['nis'] ?? '';
$nip      = $_POST['nip'] ?? '';
$id_mapel = $_POST['id_mapel'] ?? '';
$nilai    = $_POST['nilai'] ?? '';

if ($nis == '' || $nip == '' || $id_mapel == '' || $nilai == '') {
    die("Semua data nilai wajib diisi.");
}

$nis      = mysqli_real_escape_string($koneksi, $nis);
$nip      = mysqli_real_escape_string($koneksi, $nip);
$id_mapel = mysqli_real_escape_string($koneksi, $id_mapel);
$nilai    = mysqli_real_escape_string($koneksi, $nilai);

$query = mysqli_query($koneksi, "
    INSERT INTO nilai (nis, nip, id_mapel, nilai)
    VALUES ('$nis', '$nip', '$id_mapel', '$nilai')
");

if (!$query) {
    die("GAGAL MENYIMPAN NILAI: " . mysqli_error($koneksi));
}

header("Location: index.php?page=nilai/index");
exit;

?>