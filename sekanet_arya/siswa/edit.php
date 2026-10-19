<?php
include "koneksi.php";

$nis = $_GET['nis'];

$query = mysqli_query($koneksi,
"SELECT * FROM siswa WHERE nis='$nis'");

$data = mysqli_fetch_array($query);


if(!$data){

    echo "Data siswa tidak ditemukan";
    exit;

}

?>


<div class="card shadow">


<div class="card-header bg-success text-white">

<h4 class="mb-0">

<i class="bi bi-pencil-square"></i>

Edit Data Siswa

</h4>

</div>



<div class="card-body">


<form action="?page=siswa/update" method="POST">



<input type="hidden" 
name="nis_lama"
value="<?= $data['nis']; ?>">



<div class="mb-3">

<label class="form-label fw-bold">

<i class="bi bi-card-text"></i>

NIS

</label>


<input type="text"
name="nis"
class="form-control"
value="<?= $data['nis']; ?>"
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
value="<?= $data['nama']; ?>"
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
required><?= $data['alamat']; ?></textarea>


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

Update

</button>


</div>


</form>


</div>


</div>