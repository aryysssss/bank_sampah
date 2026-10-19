<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

<i class="bi bi-person-plus-fill"></i>

Tambah Data Guru

</h4>

</div>


<div class="card-body">


<form action="?page=guru/simpan" method="POST">


<div class="mb-3">

<label class="form-label fw-bold text-primary">

<i class="bi bi-person-badge-fill"></i>

NIP

</label>


<input type="text"
name="nip"
class="form-control"
placeholder="Masukkan NIP guru"
required>

</div>



<div class="mb-3">

<label class="form-label fw-bold text-primary">

<i class="bi bi-person-fill"></i>

Nama Guru

</label>


<input type="text"
name="nama"
class="form-control"
placeholder="Masukkan nama guru"
required>

</div>




<div class="mb-3">

<label class="form-label fw-bold text-primary">

<i class="bi bi-house-fill"></i>

Alamat

</label>


<textarea name="alamat"
class="form-control"
rows="3"
placeholder="Masukkan alamat guru"
required></textarea>

</div>




<div class="d-flex justify-content-between">


<a href="?page=guru/index"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Kembali

</a>



<button type="submit"
class="btn btn-primary">

<i class="bi bi-save"></i>

Simpan

</button>


</div>


</form>


</div>

</div>