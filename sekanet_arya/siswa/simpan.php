<?php
include "koneksi.php";
$nis=$_POST['nis'];
$nama=$_POST['nama'];
$alamat=$_POST['alamat'];
$query=mysqli_query($koneksi,"insert into siswa(nis,nama,alamat) values('$nis','$nama','$alamat')");
header('location:?page=siswa/index');
?>