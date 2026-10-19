<?php

include "koneksi.php";


$nis_lama = $_POST['nis_lama'];
$nis = $_POST['nis'];
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];



$query = mysqli_query($koneksi,

"UPDATE siswa SET

nis='$nis',
nama='$nama',
alamat='$alamat'

WHERE nis='$nis_lama'");



if($query){

    header("location:index.php?page=siswa/index");
    exit;

}else{

    echo "Gagal update data : "
    .mysqli_error($koneksi);

}

?>