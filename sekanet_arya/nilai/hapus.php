<?php
include "koneksi.php";
$id_nilai = $_GET['id_nilai'];
mysqli_query($koneksi, "DELETE FROM nilai WHERE id_nilai='$id_nilai'");
header("Location: index.php");
exit;
?>