<?php
require_once "../config/transaksi.php";
wajib_role(['admin']);

$dasar = "SELECT t.*, u.nama, u.tipe_nasabah, u.saldo
          FROM penarikan t
          JOIN users u ON t.id_user = u.id_user";

$pending = mysqli_query($koneksi, "$dasar WHERE t.status = 'pending'  ORDER BY t.tanggal ASC");
$riwayat = mysqli_query($koneksi, "$dasar WHERE t.status <> 'pending' ORDER BY t.tanggal DESC LIMIT 100");

layout_awal('Penarikan');
?>
<section class="panel">
    <h3>Menunggu Persetujuan</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr>
            <th>Tanggal</th><th>Nasabah</th><th class="angka">Saldo Saat Ini</th>
            <th class="angka">Jumlah Tarik</th><th>Aksi</th><th>Ubah</th>
        </tr></thead>
        <tbody>
        <?php $ada = false; while ($r = mysqli_fetch_assoc($pending)) { $ada = true; ?>
        <tr>
            <td><?= tgl($r['tanggal']) ?></td>
            <td><?= h($r['nama']) ?> (<?= h($r['tipe_nasabah']) ?>)</td>
            <td class="angka"><?= rp($r['saldo']) ?></td>
            <td class="angka"><?= rp($r['jumlah']) ?></td>
            <td>
                <form action="proses_tarik.php" method="POST" class="aksi">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)$r['id_tarik'] ?>">
                    <button name="aksi" value="setuju">Setujui</button>
                    <button name="aksi" value="tolak" class="bahaya">Tolak</button>
                </form>
            </td>
            <td class="aksi"><?= tombol_ubah('penarikan', $r['id_tarik']) ?></td>
        </tr>
        <?php } if (!$ada) echo '<tr><td colspan="6" class="kosong">Tidak ada penarikan yang menunggu.</td></tr>'; ?>
        </tbody>
    </table>
    </div>
</section>

<section class="panel">
    <h3>Riwayat Penarikan</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr>
            <th>Tanggal</th><th>Nasabah</th><th class="angka">Jumlah</th>
            <th>Status</th><th>Diproses</th><th>Ubah</th>
        </tr></thead>
        <tbody>
        <?php $ada = false; while ($r = mysqli_fetch_assoc($riwayat)) { $ada = true; ?>
        <tr>
            <td><?= tgl($r['tanggal']) ?></td>
            <td><?= h($r['nama']) ?> (<?= h($r['tipe_nasabah']) ?>)</td>
            <td class="angka"><?= rp($r['jumlah']) ?></td>
            <td><?= badge($r['status']) ?></td>
            <td><?= tgl($r['tanggal_proses']) ?></td>
            <td class="aksi"><?= tombol_ubah('penarikan', $r['id_tarik']) ?></td>
        </tr>
        <?php } if (!$ada) echo '<tr><td colspan="6" class="kosong">Belum ada riwayat penarikan.</td></tr>'; ?>
        </tbody>
    </table>
    </div>
    <p class="catatan">Menampilkan 100 penarikan terakhir yang sudah diproses.</p>
</section>
<?php layout_akhir(); ?>
