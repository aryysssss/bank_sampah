<?php
require_once "../config/layout.php";
wajib_role(['admin']);
csrf_cek();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$user) {
    flash('error', 'Akun tidak ditemukan!');
    pindah("users.php");
}

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $tipe     = $user['role'] === 'nasabah' ? ($_POST['tipe_nasabah'] ?? '') : null;

    if ($nama === '' || $username === '') {
        $pesan = "Nama dan username wajib diisi!";
    } elseif ($user['role'] === 'nasabah' && !in_array($tipe, ['guru', 'murid'], true)) {
        $pesan = "Tipe nasabah tidak valid!";
    } elseif ($password !== '' && strlen($password) < 6) {
        $pesan = "Password baru minimal 6 karakter!";
    } else {
        try {
            $u = mysqli_prepare($koneksi,
                "UPDATE users SET nama=?, username=?, tipe_nasabah=? WHERE id_user=?");
            mysqli_stmt_bind_param($u, "sssi", $nama, $username, $tipe, $id);
            mysqli_stmt_execute($u);

            if ($password !== '') {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $p = mysqli_prepare($koneksi, "UPDATE users SET password=? WHERE id_user=?");
                mysqli_stmt_bind_param($p, "si", $hash, $id);
                mysqli_stmt_execute($p);
            }

            // kalau yang diedit akun sendiri, segarkan nama di session
            if ($id === (int)$_SESSION['user']['id_user']) {
                $_SESSION['user']['nama']     = $nama;
                $_SESSION['user']['username'] = $username;
            }
            flash('ok', "Akun diperbarui.");
            pindah("users.php");
        } catch (mysqli_sql_exception $e) {
            $pesan = ((int)$e->getCode() === 1062)
                ? "Username '$username' sudah dipakai!"
                : "Gagal menyimpan: " . $e->getMessage();
        }
    }
}

layout_awal('Edit Akun');
if ($pesan) echo '<div class="alert error">' . h($pesan) . '</div>';
?>
<section class="panel lebar-form">
    <h3>Ubah Akun</h3>
    <form method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int)$user['id_user'] ?>">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" value="<?= h($user['nama']) ?>" required>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= h($user['username']) ?>" required>
        <p>Role: <b><?= h($user['role']) ?></b></p>
        <?php if ($user['role'] === 'nasabah') { ?>
        <label for="tipe">Tipe</label>
        <select id="tipe" name="tipe_nasabah">
            <option value="murid" <?= $user['tipe_nasabah'] === 'murid' ? 'selected' : '' ?>>Murid</option>
            <option value="guru"  <?= $user['tipe_nasabah'] === 'guru' ? 'selected' : '' ?>>Guru</option>
        </select>
        <?php } ?>
        <label for="password">Password baru (kosongkan jika tidak diganti)</label>
        <input type="password" id="password" name="password">
        <div class="aksi">
            <button type="submit">Simpan</button>
            <a class="btn terang" href="users.php">Batal</a>
        </div>
    </form>
</section>
<?php layout_akhir(); ?>
