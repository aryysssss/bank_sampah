<?php
include "koneksi.php";
session_start();

if($_SESSION['status']!="sukses"){
    header("location:../logout.php");
    exit;
}
?>

<div class="card shadow">

    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="bi bi-person-badge-fill"></i> Data Guru
        </h4>
    </div>

    <div class="card-body">

        <a href="?page=guru/tambah" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i>
            Tambah Data
        </a>

        <table class="table table-bordered table-hover table-striped">

            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Alamat</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php
            $no = 1;
            $query = mysqli_query($koneksi,"SELECT * FROM guru");

            while($data = mysqli_fetch_array($query)){
            ?>

            <tr>
                <td><?= $no++; ?></td>
                <td><?= $data['nip']; ?></td>
                <td><?= $data['nama']; ?></td>
                <td><?= $data['alamat']; ?></td>

                <td>
                    <a href="?page=guru/edit&nip=<?= $data['nip']; ?>" class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <a href="?page=guru/hapus&nip=<?= $data['nip']; ?>" class="btn btn-danger btn-sm"
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