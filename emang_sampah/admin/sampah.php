<?php
require_once "../config/layout.php";
wajib_role(['admin']);
csrf_cek();

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = trim($_POST['jenis'] ?? '');
    $setor = $_POST['harga_setor_per_kg'] ?? '';
    $jual  = $_POST['harga_jual_per_kg'] ?? '';

    if ($jenis === '' || !is_numeric($setor) || !is_numeric($jual) || $setor < 0 || $jual < 0) {
        $pesan = "Data tidak valid!";
    } else {
        $stmt = mysqli_prepare($koneksi,
            "INSERT INTO sampah (jenis, harga_setor_per_kg, harga_jual_per_kg) VALUES (?,?,?)");
        mysqli_stmt_bind_param($stmt, "sdd", $jenis, $setor, $jual);
        mysqli_stmt_execute($stmt);
        flash('ok', "Jenis sampah '$jenis' ditambahkan.");
        pindah("sampah.php");
    }
}

$data = mysqli_query($koneksi, "SELECT * FROM sampah ORDER BY jenis");

layout_awal('Data Sampah');
if ($pesan) echo '<div class="alert error">' . h($pesan) . '</div>';
?>
<section class="panel lebar-form">
    <h3>Tambah Jenis Sampah</h3>
    <form method="POST">
        <?= csrf_field() ?>
        <label for="jenis">Jenis sampah</label>
        <input type="text" id="jenis" name="jenis" required>
        <label for="hs">Harga setor/kg (dibayar ke nasabah)</label>
        <input type="number" id="hs" step="0.01" min="0" name="harga_setor_per_kg" required>
        <label for="hj">Harga jual/kg (ke pengepul)</label>
        <input type="number" id="hj" step="0.01" min="0" name="harga_jual_per_kg" required>
        <button type="submit">Tambah</button>
    </form>
</section>

<section class="panel">
    <h3>Daftar Sampah</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr>
            <th>Jenis</th><th class="angka">Harga Setor/kg</th><th class="angka">Harga Jual/kg</th><th class="angka">Stok</th><th>Aksi</th>
        </tr></thead>
        <tbody>
        <?php while ($s = mysqli_fetch_assoc($data)) { ?>
        <tr>
            <td><?= h($s['jenis']) ?></td>
            <td class="angka"><?= rp($s['harga_setor_per_kg']) ?></td>
            <td class="angka"><?= rp($s['harga_jual_per_kg']) ?></td>
            <td class="angka"><?= kg($s['stok_kg']) ?></td>
            <td class="aksi">
                <a class="btn terang" href="sampah_edit.php?id=<?= (int)$s['id_sampah'] ?>">Edit</a>
                <form action="sampah_hapus.php" method="POST" class="inline"
                      onsubmit="return confirm('Hapus jenis sampah ini?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)$s['id_sampah'] ?>">
                    <button type="submit" class="bahaya">Hapus</button>
                </form>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>
</section>
<?php layout_akhir(); ?>
