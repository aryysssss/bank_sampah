<?php
require_once "../config/layout.php";
wajib_role(['admin']);

$setoran_pending   = satu($koneksi, "SELECT COUNT(*) FROM setoran WHERE status='pending'");
$pembelian_pending = satu($koneksi, "SELECT COUNT(*) FROM pembelian WHERE status='pending'");
$tarik_pending     = satu($koneksi, "SELECT COUNT(*) FROM penarikan WHERE status='pending'");
$total_nasabah     = satu($koneksi, "SELECT COUNT(*) FROM users WHERE role='nasabah'");
$total_pengepul    = satu($koneksi, "SELECT COUNT(*) FROM users WHERE role='pengepul'");
$total_setoran_rp  = satu($koneksi, "SELECT COALESCE(SUM(total_rp),0) FROM setoran WHERE status='disetujui'");
$total_beli_rp     = satu($koneksi, "SELECT COALESCE(SUM(total_rp),0) FROM pembelian WHERE status='disetujui'");
$saldo_admin       = satu($koneksi, "SELECT COALESCE(SUM(saldo),0) FROM users WHERE role='admin'");
$total_pending     = $setoran_pending + $pembelian_pending + $tarik_pending;

$stok = mysqli_query($koneksi, "SELECT jenis, stok_kg FROM sampah ORDER BY jenis");

layout_awal('Dashboard Admin');
?>
<div class="bubble">
    Halo, <b><?= h($_SESSION['user']['nama']) ?></b>!
    <?= $total_pending > 0 ? "Ada $total_pending permintaan menunggu keputusanmu." : "Semua permintaan sudah beres." ?>
</div>

<div class="stats">
    <a class="stat <?= $setoran_pending ? 'merah' : '' ?>" href="setoran.php">
        <div class="angka"><?= $setoran_pending ?></div><div class="label">Setoran pending</div></a>
    <a class="stat <?= $pembelian_pending ? 'merah' : '' ?>" href="pembelian.php">
        <div class="angka"><?= $pembelian_pending ?></div><div class="label">Pembelian pending</div></a>
    <a class="stat <?= $tarik_pending ? 'merah' : '' ?>" href="tarik.php">
        <div class="angka"><?= $tarik_pending ?></div><div class="label">Penarikan pending</div></a>
    <div class="stat"><div class="angka"><?= $total_nasabah ?></div><div class="label">Nasabah</div></div>
    <div class="stat"><div class="angka"><?= $total_pengepul ?></div><div class="label">Pengepul</div></div>
    <div class="stat gelap"><div class="angka"><?= rp($saldo_admin) ?></div><div class="label">Saldo Admin</div></div>
    <div class="stat gelap"><div class="angka"><?= rp($total_setoran_rp) ?></div><div class="label">Setoran disetujui</div></div>
    <div class="stat gelap"><div class="angka"><?= rp($total_beli_rp) ?></div><div class="label">Pembelian disetujui</div></div>
</div>

<section class="panel">
    <h3>Stok Gudang</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr><th>Jenis</th><th class="angka">Stok</th></tr></thead>
        <tbody>
        <?php while ($s = mysqli_fetch_assoc($stok)) { ?>
        <tr><td><?= h($s['jenis']) ?></td><td class="angka"><?= kg($s['stok_kg']) ?></td></tr>
        <?php } ?>
        </tbody>
    </table>
    </div>
</section>
<?php layout_akhir(); ?>
