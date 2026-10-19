<?php
include "koneksi.php";

$id_mapel = $_GET['id_mapel'];

$query = mysqli_query($koneksi,
"DELETE FROM mapel WHERE id_mapel='$id_mapel'");


if($query){

    header("location:index.php?page=mapel/index");
    exit;

}else{

    echo "Gagal menghapus data: " . mysqli_error($koneksi);

}

?>