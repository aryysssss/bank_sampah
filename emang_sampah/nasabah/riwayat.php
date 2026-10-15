<?php
require_once "../config/layout.php";
wajib_role(['nasabah']);

$id_user = (int)$_SESSION['user']['id_user'];

$stmt = mysqli_prepare($koneksi, "
    SELECT s.*, sm.jenis FROM setoran s
    JOIN sampah sm ON s.id_sampah = sm.id_sampah
    WHERE s.id_user = ? ORDER BY s.tanggal DESC");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

$saldo = satu($koneksi, "SELECT saldo FROM users WHERE id_user = $id_user");

layout_awal('Riwayat Setoran');
?>
<div class="stats"><div class="stat gelap"><div class="angka"><?= rp($saldo) ?></div><div class="label">Saldo saat ini</div></div></div>

<section class="panel">
    <h3>Semua Setoran</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr><th>Tanggal</th><th>Jenis</th><th class="angka">Berat</th><th class="angka">Total</th><th>Status</th></tr></thead>
        <tbody>
        <?php $ada = false; while ($r = mysqli_fetch_assoc($hasil)) { $ada = true; ?>
        <tr>
            <td><?= tgl($r['tanggal']) ?></td>
            <td><?= h($r['jenis']) ?></td>
            <td class="angka"><?= kg($r['berat_kg']) ?></td>
            <td class="angka"><?= rp($r['total_rp']) ?></td>
            <td><?= badge($r['status']) ?></td>
        </tr>
        <?php } if (!$ada) echo '<tr><td colspan="5" class="kosong">Belum ada setoran. Yuk setor sampah pertamamu!</td></tr>'; ?>
        </tbody>
    </table>
    </div>
</section>
<?php layout_akhir(); ?>
