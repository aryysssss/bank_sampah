```php
<?php
include "koneksi.php";

$nip = $_GET['nip'];

$query = mysqli_query($koneksi, "SELECT * FROM guru WHERE nip='$nip'");
$data = mysqli_fetch_array($query);

if (!$data) {
    echo "Data guru tidak ditemukan";
    exit;
}
?>

<div class="card shadow">

    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="bi bi-pencil-square"></i>
            Edit Data Guru
        </h4>
    </div>

    <div class="card-body">

        <form action="?page=guru/update" method="POST">

            <!-- NIP lama untuk mencari data -->
            <input type="hidden" name="nip_lama" value="<?= htmlspecialchars($data['nip']); ?>">

            <div class="mb-3">
                <label class="form-label fw-bold text-primary">
                    <i class="bi bi-person-badge-fill"></i>
                    NIP
                </label>

                <input type="text"
                       name="nip"
                       class="form-control"
                       value="<?= htmlspecialchars($data['nip']); ?>"
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
                       value="<?= htmlspecialchars($data['nama']); ?>"
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
                          required><?= htmlspecialchars($data['alamat']); ?></textarea>
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
                    Update
                </button>

            </div>

        </form>

    </div>
</div>
```
