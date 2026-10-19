<?php

include "koneksi.php";


$nis = $_GET['nis'];


$query = mysqli_query($koneksi,
"DELETE FROM siswa WHERE nis='$nis'");


if($query){

    header("location:index.php");

}else{

    echo "Gagal menghapus : "
    .mysqli_error($koneksi);

}

?>
