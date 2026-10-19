<?php
include "koneksi.php";


$nip    = isset($_POST['nip']) ? $_POST['nip'] : '';
$nama   = isset($_POST['nama']) ? $_POST['nama'] : '';
$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';


if (empty($nip)) {
    echo "NIP tidak boleh kosong! <a href='tambah.php'>Kembali</a>";
    exit;
}


$query = mysqli_query($koneksi, "INSERT INTO guru (nip, nama, alamat) VALUES ('$nip', '$nama', '$alamat')");

if ($query) {
    header("location:?page=guru/index");
} else {
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
}
?>