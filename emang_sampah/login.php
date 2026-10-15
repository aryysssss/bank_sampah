<?php
require_once "config/layout.php";

// sudah login? langsung ke dashboard masing-masing
if (isset($_SESSION['user'])) {
    pindah(BASE_URL . '/' . $_SESSION['user']['role'] . '/dashboard.php');
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($koneksi,
        "SELECT id_user, nama, username, password, role, tipe_nasabah FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);      // cegah session fixation
        unset($user['password']);         // jangan simpan hash di session
        $_SESSION['user'] = $user;
        pindah(BASE_URL . '/' . $user['role'] . '/dashboard.php');
    }
    $error = "Username atau password salah!";
}

layout_awal('Login', true);
?>
<div class="login-box">
    <span class="sfx a">GOGOGO</span>
    <?= sigil('sigil') ?>
    <h1>Bank Sampah</h1>
    <div class="sub">MASUK</div>

    <?php if ($error) echo '<div class="alert error" style="margin-bottom:18px">' . h($error) . '</div>'; ?>

    <form method="POST" autocomplete="off">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= h($_POST['username'] ?? '') ?>" required autofocus>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Masuk</button>
    </form>
    <span class="sfx b">ZUUUN</span>
</div>
<?php layout_akhir(true); ?>
