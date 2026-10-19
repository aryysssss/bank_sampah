<?php
include "koneksi.php";
session_start();

if(!isset($_SESSION['status']) || $_SESSION['status']!="sukses"){
    header("location:../logout.php");
    exit;
}
?>

<div class="card shadow">

<div class="card-header bg-warning text-dark">
    <h4 class="mb-0">
        <i class="bi bi-book-fill"></i> Data Mata Pelajaran
    </h4>
</div>


<div class="card-body">


<a href="?page=mapel/tambah" class="btn btn-warning mb-3">
    <i class="bi bi-plus-circle"></i>
    Tambah Data
</a>


<table class="table table-bordered table-hover table-striped">


<thead class="table-warning">

<tr>
    <th>No</th>
    <th>ID Mapel</th>
    <th>Nama Mata Pelajaran</th>
    <th>Guru</th>
    <th width="180">Aksi</th>
</tr>

</thead>


<tbody>

<?php

$no=1;

$query=mysqli_query($koneksi,"SELECT * FROM mapel");


while($data=mysqli_fetch_array($query)){

?>


<tr>

<td><?= $no++; ?></td>

<td><?= $data['id_mapel']; ?></td>

<td><?= $data['nama_mapel']; ?></td>

<td><?= $data['guru']; ?></td>


<td>


<a href="?page=mapel/edit&id_mapel=<?= $data['id_mapel']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>


<a href="?page=mapel/hapus&id_mapel=<?= $data['id_mapel']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Yakin ingin menghapus data ini?')">

Hapus

</a>


</td>


</tr>


<?php } ?>


</tbody>

</table>


</div>

</div>