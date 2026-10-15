<?php
require_once "../config/transaksi.php";
wajib_role(['admin']);

$dasar = "SELECT p.*, u.nama, sm.jenis, sm.stok_kg
          FROM pembelian p
          JOIN users u   ON p.id_pengepul = u.id_user
          JOIN sampah sm ON p.id_sampah = sm.id_sampah";

$pending = mysqli_query($koneksi, "$dasar WHERE p.status = 'pending'  ORDER BY p.tanggal ASC");
$riwayat = mysqli_query($koneksi, "$dasar WHERE p.status <> 'pending' ORDER BY p.tanggal DESC LIMIT 100");

layout_awal('Pembelian');
?>
<section class="panel">
    <h3>Menunggu Persetujuan</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr>
            <th>Tanggal</th><th>Pengepul</th><th>Jenis</th><th class="angka">Berat</th>
            <th class="angka">Stok Saat Ini</th><th class="angka">Total</th><th>Aksi</th><th>Ubah</th>
        </tr></thead>
        <tbody>
        <?php $ada = false; while ($r = mysqli_fetch_assoc($pending)) { $ada = true; ?>
        <tr>
            <td><?= tgl($r['tanggal']) ?></td>
            <td><?= h($r['nama']) ?></td>
            <td><?= h($r['jenis']) ?></td>
            <td class="angka"><?= kg($r['berat_kg']) ?></td>
            <td class="angka"><?= kg($r['stok_kg']) ?></td>
            <td class="angka"><?= rp($r['total_rp']) ?></td>
            <td>
                <form action="proses_pembelian.php" method="POST" class="aksi">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)$r['id_beli'] ?>">
                    <button name="aksi" value="setuju">Setujui</button>
                    <button name="aksi" value="tolak" class="bahaya">Tolak</button>
                </form>
            </td>
            <td class="aksi"><?= tombol_ubah('pembelian', $r['id_beli']) ?></td>
        </tr>
        <?php } if (!$ada) echo '<tr><td colspan="8" class="kosong">Tidak ada pembelian yang menunggu.</td></tr>'; ?>
        </tbody>
    </table>
    </div>
</section>

<section class="panel">
    <h3>Riwayat Pembelian</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr>
            <th>Tanggal</th><th>Pengepul</th><th>Jenis</th><th class="angka">Berat</th>
            <th class="angka">Total</th><th>Status</th><th>Diproses</th><th>Ubah</th>
        </tr></thead>
        <tbody>
        <?php $ada = false; while ($r = mysqli_fetch_assoc($riwayat)) { $ada = true; ?>
        <tr>
            <td><?= tgl($r['tanggal']) ?></td>
            <td><?= h($r['nama']) ?></td>
            <td><?= h($r['jenis']) ?></td>
            <td class="angka"><?= kg($r['berat_kg']) ?></td>
            <td class="angka"><?= rp($r['total_rp']) ?></td>
            <td><?= badge($r['status']) ?></td>
            <td><?= tgl($r['tanggal_proses']) ?></td>
            <td class="aksi"><?= tombol_ubah('pembelian', $r['id_beli']) ?></td>
        </tr>
        <?php } if (!$ada) echo '<tr><td colspan="8" class="kosong">Belum ada riwayat pembelian.</td></tr>'; ?>
        </tbody>
    </table>
    </div>
    <p class="catatan">Menampilkan 100 pembelian terakhir yang sudah diproses.</p>
</section>
<?php layout_akhir(); ?>
