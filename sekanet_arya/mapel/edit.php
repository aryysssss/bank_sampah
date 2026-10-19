```php
<?php
include "koneksi.php";

$id_mapel = $_GET['id_mapel'];

$query = mysqli_query($koneksi,
    "SELECT * FROM mapel WHERE id_mapel='$id_mapel'"
);

$data = mysqli_fetch_array($query);

if (!$data) {
    echo "Data tidak ditemukan";
    exit;
}
?>

<div class="card shadow">

    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">
            <i class="bi bi-pencil-square"></i>
            Edit Mata Pelajaran
        </h4>
    </div>

    <div class="card-body">

        <form action="?page=mapel/update" method="POST">

            <!-- ID MAPEL LAMA -->
            <input type="hidden"
                   name="id_mapel_lama"
                   value="<?= htmlspecialchars($data['id_mapel']); ?>">

            <!-- NO -->
            <div class="mb-3">

                <label class="form-label fw-bold">
                    <i class="bi bi-list-ol"></i>
                    No
                </label>

                <input type="text"
                       name="no"
                       class="form-control"
                       value="<?= htmlspecialchars($data['id_mapel']); ?>"
                       required>

            </div>

            <!-- ID MAPEL -->
            <div class="mb-3">

                <label class="form-label fw-bold">
                    <i class="bi bi-hash"></i>
                    ID Mapel
                </label>

                <input type="text"
                       name="id_mapel"
                       class="form-control"
                       value="<?= htmlspecialchars($data['id_mapel']); ?>"
                       required>

            </div>

            <!-- NAMA MAPEL -->
            <div class="mb-3">

                <label class="form-label fw-bold">
                    <i class="bi bi-book-fill"></i>
                    Nama Mata Pelajaran
                </label>

                <input type="text"
                       name="nama_mapel"
                       class="form-control"
                       value="<?= htmlspecialchars($data['nama_mapel']); ?>"
                       required>

            </div>

            <!-- GURU -->
            <div class="mb-3">

                <label class="form-label fw-bold">
                    <i class="bi bi-person-badge-fill"></i>
                    Guru Pengajar
                </label>

                <input type="text"
                       name="guru"
                       class="form-control"
                       value="<?= htmlspecialchars($data['guru']); ?>"
                       required>

            </div>

            <div class="d-flex justify-content-between">

                <a href="?page=mapel/index"
                   class="btn btn-secondary">

                    <i class="bi bi-arrow-left"></i>
                    Kembali

                </a>

                <button type="submit"
                        class="btn btn-warning">

                    <i class="bi bi-save"></i>
                    Update

                </button>

            </div>

        </form>

    </div>
</div>
```
