<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . "/../koneksi.php";

$nama_mapel = $_POST['nama_mapel'] ?? '';
$guru       = $_POST['guru'] ?? '';

if ($nama_mapel == '' || $guru == '') {
    die("Nama mata pelajaran dan guru wajib diisi.");
}

$nama_mapel = mysqli_real_escape_string($koneksi, $nama_mapel);
$guru       = mysqli_real_escape_string($koneksi, $guru);

$sql = "INSERT INTO mapel (mapel, guru)
        VALUES ('$nama_mapel', '$guru')";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal menyimpan: " . mysqli_error($koneksi));
}

echo "<script>
    alert('Data mata pelajaran berhasil disimpan!');
    window.location='../index.php?page=mapel/index';
</script>";

exit;
?>