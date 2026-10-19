<?php
include "koneksi.php";
?>

<div class="card shadow">


<div class="card-header bg-success text-white">

<h4 class="mb-0">

<i class="bi bi-person-plus-fill"></i>

Tambah Data Siswa

</h4>

</div>



<div class="card-body">


<form action="?page=siswa/simpan" method="POST">



<div class="mb-3">

<label class="form-label fw-bold">

<i class="bi bi-card-text"></i>

NIS

</label>


<input type="text"
name="nis"
class="form-control"
placeholder="Masukkan NIS siswa"
required>


</div>




<div class="mb-3">

<label class="form-label fw-bold">

<i class="bi bi-person-fill"></i>

Nama Siswa

</label>


<input type="text"
name="nama"
class="form-control"
placeholder="Masukkan nama siswa"
required>


</div>




<div class="mb-3">

<label class="form-label fw-bold">

<i class="bi bi-house-fill"></i>

Alamat

</label>


<textarea name="alamat"
class="form-control"
rows="3"
placeholder="Masukkan alamat siswa"
required></textarea>


</div>




<div class="d-flex justify-content-between">


<a href="?page=siswa/index"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Kembali

</a>



<button type="submit"
class="btn btn-success">

<i class="bi bi-save"></i>

Simpan

</button>


</div>



</form>


</div>

</div>