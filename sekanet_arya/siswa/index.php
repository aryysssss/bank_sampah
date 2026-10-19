<?php
include "koneksi.php";
session_start();

if($_SESSION['status']!="sukses"){
    header("location:../logout.php");
    exit;
}
?>

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Data Siswa</h4>
    </div>

    <div class="card-body">

        <a href="?page=siswa/tambah" class="btn btn-success mb-3">
            <i class="bi bi-plus-circle"></i> Tambah Data
        </a>

        <table class="table table-bordered table-hover">
            <thead class="table-success">
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>

<?php
$query=mysqli_query($koneksi,"SELECT * FROM siswa");

while($data=mysqli_fetch_array($query)){
?>

<tr>
    <td><?= $data['nis']; ?></td>
    <td><?= $data['nama']; ?></td>
    <td><?= $data['alamat']; ?></td>
    <td>
        <a href="?page=siswa/edit&nis=<?= $data['nis']; ?>" class="btn btn-warning btn-sm">Edit</a>

        <a href="?page=siswa/hapus&nis=<?= $data['nis']; ?>" class="btn btn-danger btn-sm"
        onclick="return confirm('Hapus data?')">Hapus</a>
    </td>
</tr>

<?php } ?>

            </tbody>
        </table>

    </div>
</div>