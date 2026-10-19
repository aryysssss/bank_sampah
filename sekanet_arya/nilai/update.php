<?php

include "koneksi.php";

$id_nilai = $_POST['id_nilai'] ?? '';
$id_mapel = $_POST['id_mapel'] ?? '';
$nis      = $_POST['nis'] ?? '';
$nip      = $_POST['nip'] ?? '';
$nilai    = $_POST['nilai'] ?? '';

$query = mysqli_query($koneksi, "
    UPDATE nilai SET
        id_mapel = '$id_mapel',
        nis = '$nis',
        nip = '$nip',
        nilai = '$nilai'
    WHERE id_nilai = '$id_nilai'
");

if (!$query) {
    die("Gagal mengupdate nilai: " . mysqli_error($koneksi));
}

header("Location: index.php?page=nilai/index");
exit;

?>