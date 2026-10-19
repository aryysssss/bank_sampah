<?php
include "koneksi.php";
?>

<div class="card shadow">

    <div class="card-header bg-success text-white">
        <h4 class="mb-0">
            <i class="bi bi-award-fill"></i>
            Data Nilai
        </h4>
    </div>

    <div class="card-body">

        <a href="?page=nilai/tambah" class="btn btn-success mb-3">
            <i class="bi bi-plus-circle"></i>
            Tambah Data
        </a>

        <table class="table table-bordered table-hover table-striped">

            <thead class="table-success">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Nilai</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php

            $no = 1;

            $query = mysqli_query($koneksi, "
                SELECT 
                    nilai.*,
                    siswa.nama AS nama_siswa,
                    mapel.mapel AS nama_mapel,
                    guru.nama AS nama_guru

                FROM nilai

                JOIN siswa 
                    ON nilai.nis = siswa.nis

                JOIN mapel 
                    ON nilai.id_mapel = mapel.id_mapel

                JOIN guru 
                    ON nilai.nip = guru.nip
            ");

            if (!$query) {
                die("ERROR QUERY NILAI: " . mysqli_error($koneksi));
            }

            while ($data = mysqli_fetch_assoc($query)) {

            ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td>
                        <?= htmlspecialchars($data['nama_siswa']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['nama_mapel']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['nama_guru']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['nilai']); ?>
                    </td>

                    <td>

                        <a
                            href="?page=nilai/edit&id_nilai=<?= $data['id_nilai']; ?>"
                            class="btn btn-warning btn-sm"
                        >
                            <i class="bi bi-pencil"></i>
                            Edit
                        </a>

                        <a
                            href="?page=nilai/hapus&id_nilai=<?= $data['id_nilai']; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus data ini?')"
                        >
                            <i class="bi bi-trash"></i>
                            Hapus
                        </a>

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>