<?php
require_once "../config/layout.php";
wajib_role(['admin']);
csrf_cek();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = mysqli_prepare($koneksi, "SELECT * FROM sampah WHERE id_sampah = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$sampah = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$sampah) {
    flash('error', 'Data sampah tidak ditemukan!');
    pindah("sampah.php");
}

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = trim($_POST['jenis'] ?? '');
    $setor = $_POST['harga_setor_per_kg'] ?? '';
    $jual  = $_POST['harga_jual_per_kg'] ?? '';

    if ($jenis === '' || !is_numeric($setor) || !is_numeric($jual) || $setor < 0 || $jual < 0) {
        $pesan = "Data tidak valid!";
    } else {
        $u = mysqli_prepare($koneksi,
            "UPDATE sampah SET jenis=?, harga_setor_per_kg=?, harga_jual_per_kg=? WHERE id_sampah=?");
        mysqli_stmt_bind_param($u, "sddi", $jenis, $setor, $jual, $id);
        mysqli_stmt_execute($u);
        flash('ok', "Jenis sampah diperbarui.");
        pindah("sampah.php");
    }
}

layout_awal('Edit Jenis Sampah');
if ($pesan) echo '<div class="alert error">' . h($pesan) . '</div>';
?>
<section class="panel lebar-form">
    <h3>Ubah Data</h3>
    <form method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int)$sampah['id_sampah'] ?>">
        <label for="jenis">Jenis</label>
        <input type="text" id="jenis" name="jenis" value="<?= h($sampah['jenis']) ?>" required>
        <label for="hs">Harga setor/kg</label>
        <input type="number" id="hs" step="0.01" min="0" name="harga_setor_per_kg" value="<?= h($sampah['harga_setor_per_kg']) ?>" required>
        <label for="hj">Harga jual/kg</label>
        <input type="number" id="hj" step="0.01" min="0" name="harga_jual_per_kg" value="<?= h($sampah['harga_jual_per_kg']) ?>" required>
        <p class="catatan">Stok: <?= kg($sampah['stok_kg']) ?> (berubah otomatis dari transaksi)</p>
        <div class="aksi" style="margin-top:16px">
            <button type="submit">Simpan</button>
            <a class="btn terang" href="sampah.php">Batal</a>
        </div>
    </form>
</section>
<?php layout_akhir(); ?>
