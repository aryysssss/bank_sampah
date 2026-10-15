<?php
require_once "../config/layout.php";
wajib_role(['pengepul']);

$id = (int)$_SESSION['user']['id_user'];
$stmt = mysqli_prepare($koneksi, "
    SELECT p.*, sm.jenis FROM pembelian p
    JOIN sampah sm ON p.id_sampah = sm.id_sampah
    WHERE p.id_pengepul = ? ORDER BY p.tanggal DESC");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

layout_awal('Riwayat Pembelian');
?>
<section class="panel">
    <h3>Semua Pembelian</h3>
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
        <?php } if (!$ada) echo '<tr><td colspan="5" class="kosong">Belum ada pembelian.</td></tr>'; ?>
        </tbody>
    </table>
    </div>
</section>
<?php layout_akhir(); ?>
