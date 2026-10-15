<?php
require_once "../config/layout.php";
wajib_role(['pengepul']);

$id = (int)$_SESSION['user']['id_user'];

$saldo    = satu($koneksi, "SELECT saldo FROM users WHERE id_user = $id");
$pending  = satu($koneksi, "SELECT COUNT(*) FROM pembelian WHERE id_pengepul = $id AND status='pending'");
$total_kg = satu($koneksi, "SELECT COALESCE(SUM(berat_kg),0) FROM pembelian WHERE id_pengepul = $id AND status='disetujui'");
$total_rp = satu($koneksi, "SELECT COALESCE(SUM(total_rp),0) FROM pembelian WHERE id_pengepul = $id AND status='disetujui'");

$stok = mysqli_query($koneksi, "SELECT jenis, harga_jual_per_kg, stok_kg FROM sampah ORDER BY jenis");

layout_awal('Dashboard Pengepul');
?>
<div class="bubble">Halo, <b><?= h($_SESSION['user']['nama']) ?></b>! Stok gudang siap diangkut.</div>

<div class="stats">
    <div class="stat"><div class="angka"><?= rp($saldo) ?></div><div class="label">Saldo Anda</div></div>
    <div class="stat <?= $pending ? 'merah' : '' ?>"><div class="angka"><?= $pending ?></div><div class="label">Pembelian menunggu persetujuan</div></div>
    <div class="stat"><div class="angka"><?= kg($total_kg) ?></div><div class="label">Total sampah dibeli</div></div>
    <div class="stat gelap"><div class="angka"><?= rp($total_rp) ?></div><div class="label">Total pengeluaran</div></div>
</div>

<section class="panel">
    <h3>Stok Tersedia</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr><th>Jenis</th><th class="angka">Harga/kg</th><th class="angka">Stok</th></tr></thead>
        <tbody>
        <?php while ($s = mysqli_fetch_assoc($stok)) { ?>
        <tr>
            <td><?= h($s['jenis']) ?></td>
            <td class="angka"><?= rp($s['harga_jual_per_kg']) ?></td>
            <td class="angka"><?= kg($s['stok_kg']) ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>
    <div class="aksi" style="margin-top:18px">
        <a class="btn" href="beli.php">Beli Sampah</a>
        <a class="btn terang" href="riwayat.php">Riwayat Pembelian</a>
    </div>
</section>
<?php layout_akhir(); ?>