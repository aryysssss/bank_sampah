<?php

include "koneksi.php";

$id_mapel_lama = $_POST['id_mapel_lama'] ?? '';
$id_mapel       = $_POST['id_mapel'] ?? '';
$nama_mapel     = $_POST['nama_mapel'] ?? '';
$guru           = $_POST['guru'] ?? '';

$query = mysqli_query($koneksi, "
    UPDATE mapel SET
        id_mapel = '$id_mapel',
        nama_mapel = '$nama_mapel',
        guru = '$guru'
    WHERE id_mapel = '$id_mapel_lama'
");

if ($query) {

    echo "<script>
            alert('Data mata pelajaran berhasil diupdate!');
            window.location='index.php?page=mapel/index';
          </script>";

} else {

    echo "<script>
            alert('Data mata pelajaran gagal diupdate!');
            window.location='index.php?page=mapel/index';
          </script>";
}

?>