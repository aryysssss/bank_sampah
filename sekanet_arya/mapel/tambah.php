
<div class="card shadow">

    <div class="card-header bg-warning text-dark">

        <h4 class="mb-0">
            <i class="bi bi-book-plus-fill"></i>
            Tambah Mata Pelajaran
        </h4>

    </div>

    <div class="card-body">

        <form action="mapel/simpan.php" method="POST">

    <div class="mb-3">

        <label class="form-label fw-bold">
            <i class="bi bi-book-fill text-warning"></i>
            Nama Mata Pelajaran
        </label>

        <input
            type="text"
            name="nama_mapel"
            class="form-control"
            placeholder="Masukkan nama mata pelajaran"
            required
        >

    </div>

    <div class="mb-3">

        <label class="form-label fw-bold">
            <i class="bi bi-person-badge-fill text-warning"></i>
            Guru Pengajar
        </label>

        <input
            type="text"
            name="guru"
            class="form-control"
            placeholder="Masukkan nama guru"
            required
        >

    </div>

    <div class="d-flex justify-content-between">

        <a href="?page=mapel/index" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

        <button type="submit" class="btn btn-warning">
            <i class="bi bi-save"></i>
            Simpan
        </button>

    </div>

</form>

    </div>

</div>
