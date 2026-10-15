<?php
require_once "../config/layout.php";
wajib_role(['nasabah']);
csrf_cek();

$id_user = (int)$_SESSION['user']['id_user'];

$saldo    = (float)satu($koneksi, "SELECT saldo FROM users WHERE id_user = $id_user");
$pending  = (float)satu($koneksi, "SELECT COALESCE(SUM(jumlah),0) FROM penarikan WHERE id_user = $id_user AND status='pending'");
$tersedia = $saldo - $pending;

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah = $_POST['jumlah'] ?? '';

    if (!is_numeric($jumlah) || $jumlah <= 0) {
        $pesan = "Jumlah tidak valid!";
    } elseif (round((float)$jumlah, 2) > $tersedia) {
        $pesan = "Saldo tidak cukup! Saldo tersedia: " . rp($tersedia);
    } else {
        $jumlah = round((float)$jumlah, 2);
        $stmt = mysqli_prepare($koneksi, "INSERT INTO penarikan (id_user, jumlah) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "id", $id_user, $jumlah);
        mysqli_stmt_execute($stmt);
        flash('ok', 'Pengajuan terkirim, menunggu persetujuan admin.');
        pindah("tarik.php");
    }
}

$stmt = mysqli_prepare($koneksi, "SELECT * FROM penarikan WHERE id_user = ? ORDER BY tanggal DESC");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$riwayat = mysqli_stmt_get_result($stmt);

layout_awal('Tarik Saldo');
if ($pesan) echo '<div class="alert error">' . h($pesan) . '</div>';
?>
<div class="stats">
    <div class="stat gelap"><div class="angka"><?= rp($saldo) ?></div><div class="label">Saldo</div></div>
    <div class="stat <?= $pending > 0 ? 'merah' : '' ?>"><div class="angka"><?= rp($pending) ?></div><div class="label">Menunggu persetujuan</div></div>
    <div class="stat"><div class="angka"><?= rp($tersedia) ?></div><div class="label">Tersedia untuk ditarik</div></div>
</div>

<section class="panel lebar-form">
    <h3>Ajukan Penarikan</h3>
    <form method="POST">
        <?= csrf_field() ?>
        <label for="jumlah">Jumlah (Rp)</label>
        <input type="number" id="jumlah" step="0.01" min="0.01" name="jumlah" required>
        <button type="submit">Ajukan Penarikan</button>
    </form>
</section>

<section class="panel">
    <h3>Riwayat Penarikan</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr><th>Tanggal</th><th class="angka">Jumlah</th><th>Status</th></tr></thead>
        <tbody>
        <?php $ada = false; while ($r = mysqli_fetch_assoc($riwayat)) { $ada = true; ?>
        <tr>
            <td><?= tgl($r['tanggal']) ?></td>
            <td class="angka"><?= rp($r['jumlah']) ?></td>
            <td><?= badge($r['status']) ?></td>
        </tr>
        <?php } if (!$ada) echo '<tr><td colspan="3" class="kosong">Belum ada penarikan.</td></tr>'; ?>
        </tbody>
    </table>
    </div>
</section>
<?php layout_akhir(); ?>
