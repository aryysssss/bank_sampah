<?php
include "koneksi.php";


$nip = isset($_GET['nip']) ? $_GET['nip'] : '';

$query = mysqli_query($koneksi, "DELETE FROM guru WHERE nip='$nip'");

if($query){
    header("location:index.php");
}else{
    echo "Gagal menghapus : " . mysqli_error($koneksi);
}
?>
