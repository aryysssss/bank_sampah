<?php
require_once "../config/transaksi.php";
wajib_role(['admin']);

$id_admin = (int)$_SESSION['user']['id_user'];

/* ---------- Proses top up (admin sendiri atau pengepul) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_cek();
    $untuk  = $_POST['untuk'] ?? '';
    $jumlah = $_POST['jumlah'] ?? '';

    if ($untuk === 'admin') {
        $id   = $id_admin;
        $role = 'admin';
    } else {
        $id   = (int)($_POST['id_user'] ?? 0);
        $role = 'pengepul';
    }

    if ($id <= 0 || !is_numeric($jumlah) || (float)$jumlah <= 0) {
        flash('error', 'Data top up tidak valid!');
    } else {
        $jumlah = round((float)$jumlah, 2);
        $u = jalankan($koneksi,
            "UPDATE users SET saldo = saldo + ? WHERE id_user = ? AND role = ?",
            "dis", [$jumlah, $id, $role]);
        if (mysqli_stmt_affected_rows($u) > 0) {
            flash('ok', 'Saldo ' . ($untuk === 'admin' ? 'admin' : 'pengepul') . ' bertambah ' . rp($jumlah) . '.');
        } else {
            flash('error', 'Akun tidak ditemukan!');
        }
    }
    pindah("topup_pengepul.php");
}

$saldo_admin = satu($koneksi, "SELECT saldo FROM users WHERE id_user = $id_admin");
$daftar = mysqli_fetch_all(mysqli_query($koneksi,
    "SELECT id_user, nama, username, saldo FROM users WHERE role = 'pengepul' ORDER BY nama"), MYSQLI_ASSOC);

/* Dropdown nominal cepat; mengisi kolom jumlah di form yang sama */
function pilihan_nominal($id_input) {
    echo '<label>Pilihan Nominal</label>';
    echo '<select onchange="if(this.value){document.getElementById(\'' . $id_input . '\').value=this.value;}">';
    echo '<option value="">-- Pilih cepat atau isi manual --</option>';
    foreach ([10000, 20000, 50000, 100000, 200000, 500000, 1000000, 2000000] as $n) {
        echo '<option value="' . $n . '">' . rp($n) . '</option>';
    }
    echo '</select>';
}

layout_awal('Top Up Saldo');
?>
<div class="stats">
    <div class="stat gelap"><div class="angka"><?= rp($saldo_admin) ?></div><div class="label">Saldo akun admin ini</div></div>
</div>

<section class="panel lebar-form">
    <h3>Tambah Saldo Admin</h3>
    <form method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="untuk" value="admin">
        <?php pilihan_nominal('jumlah_admin'); ?>
        <label for="jumlah_admin">Jumlah (Rp)</label>
        <input type="number" id="jumlah_admin" name="jumlah" min="1" step="1" required>
        <button type="submit">Tambah Saldo Admin</button>
    </form>
    <p class="catatan">Saldo admin dipakai untuk membayar setoran nasabah. Kalau saldo kurang, setoran tidak bisa disetujui.</p>
</section>

<section class="panel lebar-form">
    <h3>Top Up Saldo Pengepul</h3>
    <form method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="untuk" value="pengepul">
        <label for="id_user">Pengepul</label>
        <select id="id_user" name="id_user" required>
            <?php foreach ($daftar as $p) { ?>
            <option value="<?= (int)$p['id_user'] ?>"><?= h($p['nama']) ?> (<?= rp($p['saldo']) ?>)</option>
            <?php } ?>
        </select>
        <?php pilihan_nominal('jumlah_pengepul'); ?>
        <label for="jumlah_pengepul">Jumlah (Rp)</label>
        <input type="number" id="jumlah_pengepul" name="jumlah" min="1" step="1" required>
        <button type="submit">Top Up</button>
    </form>
</section>

<section class="panel">
    <h3>Saldo Semua Pengepul</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr><th>Nama</th><th>Username</th><th class="angka">Saldo</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $p) { ?>
        <tr>
            <td><?= h($p['nama']) ?></td>
            <td><?= h($p['username']) ?></td>
            <td class="angka"><?= rp($p['saldo']) ?></td>
        </tr>
        <?php } if (!$daftar) echo '<tr><td colspan="3" class="kosong">Belum ada pengepul.</td></tr>'; ?>
        </tbody>
    </table>
    </div>
</section>
<?php layout_akhir(); ?>
