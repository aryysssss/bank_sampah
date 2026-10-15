<?php
require_once "../config/layout.php";
wajib_role(['pengepul']);

$saldo = satu($koneksi, "SELECT saldo FROM users WHERE id_user = " . (int)$_SESSION['user']['id_user']);
$data = mysqli_query($koneksi, "SELECT * FROM sampah ORDER BY jenis");

layout_awal('Beli Sampah');
?>
<section class="panel lebar-form">
    <h3>Form Pembelian</h3>
    <form action="simpan.php" method="POST">
        <?= csrf_field() ?>
        <label for="jenis">Jenis sampah</label>
        <select id="jenis" name="id_sampah" required>
            <?php while ($s = mysqli_fetch_assoc($data)) { ?>
            <option value="<?= (int)$s['id_sampah'] ?>" data-harga="<?= h($s['harga_jual_per_kg']) ?>">
                <?= h($s['jenis']) ?> (<?= rp($s['harga_jual_per_kg']) ?>/kg, stok <?= kg($s['stok_kg']) ?>)
            </option>
            <?php } ?>
        </select>
        <label for="berat">Berat (kg)</label>
        <input type="number" id="berat" step="0.01" min="0.01" name="berat_kg" required>
        <p class="taksiran">Taksiran: <span id="taksir">Rp 0</span> &bull; Saldo Anda: <?= rp($saldo) ?></p>
        <button type="submit">Ajukan Pembelian</button>
    </form>
    <p class="catatan">Saldo langsung dipotong saat diajukan. Stok berkurang setelah disetujui admin; jika ditolak, saldo dikembalikan.</p>
</section>

<script>
const jenis = document.getElementById('jenis'), berat = document.getElementById('berat'), taksir = document.getElementById('taksir');
function hitung() {
    const harga = parseFloat(jenis.selectedOptions[0]?.dataset.harga || 0);
    const total = harga * (parseFloat(berat.value) || 0);
    taksir.textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
}
jenis.addEventListener('change', hitung);
berat.addEventListener('input', hitung);
hitung();
</script>
<?php layout_akhir(); ?>
