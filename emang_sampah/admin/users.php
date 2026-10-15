<?php
require_once "../config/layout.php";
wajib_role(['admin']);
csrf_cek();

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? '';
    $tipe     = $_POST['tipe_nasabah'] ?? '';

    if (!in_array($role, ['admin', 'nasabah', 'pengepul'], true)) {
        $pesan = "Role tidak valid!";
    } elseif ($role === 'nasabah' && !in_array($tipe, ['guru', 'murid'], true)) {
        $pesan = "Nasabah harus dipilih guru atau murid!";
    } elseif ($nama === '' || $username === '' || strlen($password) < 6) {
        $pesan = "Nama, username wajib diisi dan password minimal 6 karakter!";
    } else {
        if ($role !== 'nasabah') $tipe = null;
        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = mysqli_prepare($koneksi,
                "INSERT INTO users (nama, username, password, role, tipe_nasabah) VALUES (?,?,?,?,?)");
            mysqli_stmt_bind_param($stmt, "sssss", $nama, $username, $hash, $role, $tipe);
            mysqli_stmt_execute($stmt);
            flash('ok', "Akun '$username' berhasil dibuat.");
            pindah("users.php");
        } catch (mysqli_sql_exception $e) {
            $pesan = ((int)$e->getCode() === 1062)
                ? "Username '$username' sudah dipakai!"
                : "Gagal membuat akun: " . $e->getMessage();
        }
    }
}

$data = mysqli_query($koneksi, "SELECT * FROM users ORDER BY role, nama");

layout_awal('Kelola Users');
if ($pesan) echo '<div class="alert error">' . h($pesan) . '</div>';
?>
<section class="panel">
    <h3>Tambah Akun</h3>
    <form method="POST">
        <?= csrf_field() ?>
        <div class="form-grid">
            <div><label for="nama">Nama lengkap</label>
                <input type="text" id="nama" name="nama" value="<?= h($_POST['nama'] ?? '') ?>" required></div>
            <div><label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= h($_POST['username'] ?? '') ?>" required></div>
            <div><label for="password">Password (min. 6 karakter)</label>
                <input type="password" id="password" name="password" required></div>
            <div><label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="nasabah">Nasabah</option>
                    <option value="pengepul">Pengepul</option>
                    <option value="admin">Admin</option>
                </select></div>
            <div id="wrap-tipe"><label for="tipe">Tipe nasabah</label>
                <select id="tipe" name="tipe_nasabah">
                    <option value="murid">Murid</option>
                    <option value="guru">Guru</option>
                </select></div>
        </div>
        <button type="submit">Tambah</button>
    </form>
</section>

<section class="panel">
    <h3>Daftar Akun</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr>
            <th>Nama</th><th>Username</th><th>Role</th><th>Tipe</th><th class="angka">Saldo</th><th>Aksi</th>
        </tr></thead>
        <tbody>
        <?php while ($u = mysqli_fetch_assoc($data)) { ?>
        <tr>
            <td><?= h($u['nama']) ?></td>
            <td><?= h($u['username']) ?></td>
            <td><?= h($u['role']) ?></td>
            <td><?= h($u['tipe_nasabah'] ?? '-') ?></td>
            <td class="angka"><?= $u['role'] === 'nasabah' ? rp($u['saldo']) : '-' ?></td>
            <td class="aksi">
                <a class="btn terang" href="users_edit.php?id=<?= (int)$u['id_user'] ?>">Edit</a>
                <?php if ((int)$u['id_user'] !== (int)$_SESSION['user']['id_user']) { ?>
                <form action="users_hapus.php" method="POST" class="inline"
                      onsubmit="return confirm('Hapus akun ini?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)$u['id_user'] ?>">
                    <button type="submit" class="bahaya">Hapus</button>
                </form>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>
</section>

<script>
// tipe nasabah hanya relevan kalau role = nasabah
const role = document.getElementById('role'), wrapTipe = document.getElementById('wrap-tipe');
function cekRole() { wrapTipe.style.display = role.value === 'nasabah' ? 'block' : 'none'; }
role.addEventListener('change', cekRole);
cekRole();
</script>
<?php layout_akhir(); ?>
