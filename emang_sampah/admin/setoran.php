<?php
require_once "../config/transaksi.php";
wajib_role(['admin']);

$dasar = "SELECT s.*, u.nama, u.tipe_nasabah, sm.jenis
          FROM setoran s
          JOIN users u   ON s.id_user = u.id_user
          JOIN sampah sm ON s.id_sampah = sm.id_sampah";

$pending = mysqli_query($koneksi, "$dasar WHERE s.status = 'pending'  ORDER BY s.tanggal ASC");
$riwayat = mysqli_query($koneksi, "$dasar WHERE s.status <> 'pending' ORDER BY s.tanggal DESC LIMIT 100");

layout_awal('Setoran');
?>
<section class="panel">
    <h3>Menunggu Persetujuan</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr>
            <th>Tanggal</th><th>Nasabah</th><th>Jenis</th><th class="angka">Berat</th>
            <th class="angka">Total</th><th>Aksi</th><th>Ubah</th>
        </tr></thead>
        <tbody>
        <?php $ada = false; while ($r = mysqli_fetch_assoc($pending)) { $ada = true; ?>
        <tr>
            <td><?= tgl($r['tanggal']) ?></td>
            <td><?= h($r['nama']) ?> (<?= h($r['tipe_nasabah']) ?>)</td>
            <td><?= h($r['jenis']) ?></td>
            <td class="angka"><?= kg($r['berat_kg']) ?></td>
            <td class="angka"><?= rp($r['total_rp']) ?></td>
            <td>
                <form action="proses_setoran.php" method="POST" class="aksi">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)$r['id_setoran'] ?>">
                    <button name="aksi" value="setuju">Setujui</button>
                    <button name="aksi" value="tolak" class="bahaya">Tolak</button>
                </form>
            </td>
            <td class="aksi"><?= tombol_ubah('setoran', $r['id_setoran']) ?></td>
        </tr>
        <?php } if (!$ada) echo '<tr><td colspan="7" class="kosong">Tidak ada setoran yang menunggu.</td></tr>'; ?>
        </tbody>
    </table>
    </div>
</section>

<section class="panel">
    <h3>Riwayat Setoran</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr>
            <th>Tanggal</th><th>Nasabah</th><th>Jenis</th><th class="angka">Berat</th>
            <th class="angka">Total</th><th>Status</th><th>Diproses</th><th>Ubah</th>
        </tr></thead>
        <tbody>
        <?php $ada = false; while ($r = mysqli_fetch_assoc($riwayat)) { $ada = true; ?>
        <tr>
            <td><?= tgl($r['tanggal']) ?></td>
            <td><?= h($r['nama']) ?> (<?= h($r['tipe_nasabah']) ?>)</td>
            <td><?= h($r['jenis']) ?></td>
            <td class="angka"><?= kg($r['berat_kg']) ?></td>
            <td class="angka"><?= rp($r['total_rp']) ?></td>
            <td><?= badge($r['status']) ?></td>
            <td><?= tgl($r['tanggal_proses']) ?></td>
            <td class="aksi"><?= tombol_ubah('setoran', $r['id_setoran']) ?></td>
        </tr>
        <?php } if (!$ada) echo '<tr><td colspan="8" class="kosong">Belum ada riwayat setoran.</td></tr>'; ?>
        </tbody>
    </table>
    </div>
    <p class="catatan">Menampilkan 100 setoran terakhir yang sudah diproses.</p>
</section>
<?php layout_akhir(); ?>
