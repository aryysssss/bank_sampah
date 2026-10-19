<?php
include "koneksi.php";
?>

<div class="card shadow">

<div class="card-header bg-success text-white">
    <h4 class="mb-0">
        <i class="bi bi-plus-circle"></i> Tambah Data Nilai
    </h4>
</div>


<div class="card-body">

<form action="?page=nilai/simpan" method="POST">


<div class="mb-3">

<label class="form-label">
Nama Siswa
</label>

<select name="nis" class="form-select" required>

<option value="">
-- Pilih Siswa --
</option>

<?php

$query_siswa = mysqli_query($koneksi,"SELECT * FROM siswa");

while($data=mysqli_fetch_array($query_siswa)){

?>

<option value="<?= $data['nis']; ?>">

<?= $data['nama']; ?>

</option>

<?php } ?>

</select>

</div>



<div class="mb-3">

<label class="form-label">
Guru
</label>

<select name="nip" class="form-select" required>

<option value="">
-- Pilih Guru --
</option>

<?php

$query_guru=mysqli_query($koneksi,"SELECT * FROM guru");

while($data=mysqli_fetch_array($query_guru)){

?>

<option value="<?= $data['nip']; ?>">

<?= $data['nama']; ?>

</option>

<?php } ?>

</select>

</div>



<div class="mb-3">

<label class="form-label">
Mata Pelajaran
</label>

<select name="id_mapel" class="form-select" required>

<option value="">
-- Pilih Mapel --
</option>

<?php

$query_mapel=mysqli_query($koneksi,"SELECT * FROM mapel");

while($data=mysqli_fetch_array($query_mapel)){

?>

<option value="<?= $data['id_mapel']; ?>">

<?= $data['mapel']; ?>

</option>

<?php } ?>

</select>

</div>



<div class="mb-3">

<label class="form-label">
Nilai
</label>

<input type="number"
name="nilai"
class="form-control"
min="0"
max="100"
required>

</div>



<button type="submit" class="btn btn-success">
Simpan
</button>


<a href="?page=nilai/index" class="btn btn-secondary">
Kembali
</a>


</form>

</div>

</div>