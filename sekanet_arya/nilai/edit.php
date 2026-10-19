<?php
include "koneksi.php";

$id_nilai = $_GET['id_nilai'];

$query = mysqli_query($koneksi,
"SELECT * FROM nilai WHERE id_nilai='$id_nilai'");

$data = mysqli_fetch_array($query);

if(!$data){
    echo "Data nilai tidak ditemukan";
    exit;
}

?>


<div class="card shadow">


<div class="card-header bg-success text-white">

<h4 class="mb-0">

<i class="bi bi-pencil-square"></i>

Edit Data Nilai

</h4>

</div>



<div class="card-body">


<form action="?page=nilai/update" method="POST">


<input type="hidden" 
name="id_nilai" 
value="<?= $data['id_nilai']; ?>">



<div class="mb-3">

<label class="form-label fw-bold text-success">

<i class="bi bi-person-fill"></i>

Nama Siswa

</label>


<select name="nis" class="form-select" required>


<option value="">
-- Pilih Siswa --
</option>


<?php

$siswa = mysqli_query($koneksi,"SELECT * FROM siswa");

while($s = mysqli_fetch_array($siswa)){

?>

<option value="<?= $s['nis']; ?>"
<?= ($s['nis']==$data['nis']) ? 'selected' : ''; ?>>

<?= $s['nama']; ?>

</option>


<?php } ?>


</select>

</div>




<div class="mb-3">

<label class="form-label fw-bold text-success">

<i class="bi bi-person-badge-fill"></i>

Guru

</label>


<select name="nip" class="form-select" required>


<option value="">
-- Pilih Guru --
</option>


<?php

$guru = mysqli_query($koneksi,"SELECT * FROM guru");

while($g = mysqli_fetch_array($guru)){

?>


<option value="<?= $g['nip']; ?>"
<?= ($g['nip']==$data['nip']) ? 'selected' : ''; ?>>

<?= $g['nama']; ?>

</option>


<?php } ?>


</select>


</div>




<div class="mb-3">

<label class="form-label fw-bold text-success">

<i class="bi bi-book-fill"></i>

Mata Pelajaran

</label>


<select name="id_mapel" class="form-select" required>


<option value="">
-- Pilih Mapel --
</option>


<?php

$mapel = mysqli_query($koneksi,"SELECT * FROM mapel");

while($m = mysqli_fetch_array($mapel)){

?>


<option value="<?= $m['id_mapel']; ?>"
<?= ($m['id_mapel']==$data['id_mapel']) ? 'selected' : ''; ?>>

<?= $m['nama_mapel']; ?>

</option>


<?php } ?>


</select>


</div>




<div class="mb-3">


<label class="form-label fw-bold text-success">

<i class="bi bi-award-fill"></i>

Nilai

</label>


<input type="number"
name="nilai"
class="form-control"
value="<?= $data['nilai']; ?>"
min="0"
max="100"
required>


</div>




<div class="d-flex justify-content-between">


<a href="?page=nilai/index"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Kembali

</a>




<button type="submit"
class="btn btn-success">

<i class="bi bi-save"></i>

Update

</button>


</div>



</form>


</div>


</div>