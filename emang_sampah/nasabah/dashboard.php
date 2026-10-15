<?php
require_once "../config/layout.php";
wajib_role(['nasabah']);

$id = (int)$_SESSION['user']['id_user'];

$saldo    = satu($koneksi, "SELECT saldo FROM users WHERE id_user = $id");
$pending  = satu($koneksi, "SELECT COUNT(*) FROM setoran WHERE id_user = $id AND status='pending'");
$total_kg = satu($koneksi, "SELECT COALESCE(SUM(berat_kg),0) FROM setoran WHERE id_user = $id AND status='disetujui'");
$total_rp = satu($koneksi, "SELECT COALESCE(SUM(total_rp),0) FROM setoran WHERE id_user = $id AND status='disetujui'");

layout_awal('Dashboard Nasabah');
?>
<div class="bubble">
    Halo, <b><?= h($_SESSION['user']['nama']) ?></b>
    <?php if ($_SESSION['user']['tipe_nasabah']) echo '(' . h($_SESSION['user']['tipe_nasabah']) . ')'; ?>!
    Ayo kumpulkan sampahmu hari ini.
</div>

<div class="stats">
    <div class="stat gelap"><div class="angka"><?= rp($saldo) ?></div><div class="label">Saldo kamu</div></div>
    <div class="stat <?= $pending ? 'merah' : '' ?>"><div class="angka"><?= $pending ?></div><div class="label">Setoran menunggu persetujuan</div></div>
    <div class="stat"><div class="angka"><?= kg($total_kg) ?></div><div class="label">Total sampah disetujui</div></div>
    <div class="stat"><div class="angka"><?= rp($total_rp) ?></div><div class="label">Total pendapatan setoran</div></div>
</div>

<section class="panel">
    <h3>Mau Ngapain?</h3>
    <div class="aksi">
        <a class="btn" href="setor.php">Setor Sampah</a>
        <a class="btn terang" href="tarik.php">Tarik Saldo</a>
        <a class="btn terang" href="riwayat.php">Riwayat Setoran</a>
    </div>
</section>
<?php layout_akhir(); ?>
