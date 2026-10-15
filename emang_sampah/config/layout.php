<?php
require_once __DIR__ . "/auth.php";

/* ---------- Helper tampilan ---------- */
function h($s)   { return htmlspecialchars((string)($s ?? ''), ENT_QUOTES, 'UTF-8'); }
function rp($n)  { return 'Rp ' . number_format((float)$n, 0, ',', '.'); }
function kg($n)  { return rtrim(rtrim(number_format((float)$n, 2, ',', '.'), '0'), ',') . ' kg'; }
function tgl($t) { return $t ? date('d/m/Y H:i', strtotime($t)) : '-'; }
function badge($s) {
    $s = in_array($s, ['pending', 'disetujui', 'ditolak'], true) ? $s : 'pending';
    return '<span class="badge ' . $s . '">' . $s . '</span>';
}
function satu($koneksi, $sql) {
    $r = mysqli_fetch_row(mysqli_query($koneksi, $sql));
    return $r[0] ?? 0;
}

function menu_role($role) {
    $m = [
        'admin' => [
            ['Dashboard', 'admin/dashboard.php'], ['Setoran', 'admin/setoran.php'],
            ['Pembelian', 'admin/pembelian.php'], ['Penarikan', 'admin/tarik.php'],
            ['Top Up', 'admin/topup_pengepul.php'],
            ['Sampah', 'admin/sampah.php'], ['Transaksi', 'admin/transaksi.php'],
            ['Grafik', 'admin/grafik.php'], ['Users', 'admin/users.php'],
        ],
        'nasabah' => [
            ['Dashboard', 'nasabah/dashboard.php'], ['Setor', 'nasabah/setor.php'],
            ['Tarik', 'nasabah/tarik.php'], ['Riwayat', 'nasabah/riwayat.php'],
        ],
        'pengepul' => [
            ['Dashboard', 'pengepul/dashboard.php'], ['Beli', 'pengepul/beli.php'],
            ['Riwayat', 'pengepul/riwayat.php'],
        ],
    ];
    return $m[$role] ?? [];
}

function sigil($kelas = '') {
    // lambang buatan sendiri: lingkaran, bilah pedang, goresan
    return '<svg class="' . $kelas . '" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="square">'
         . '<circle cx="32" cy="32" r="26"/><path d="M32 6V58"/><path d="M20 22H44"/>'
         . '<path d="M14 50L50 14" stroke-width="3"/><path d="M22 36H42" stroke-width="2"/></svg>';
}

/* ---------- Kerangka halaman ---------- */
function layout_awal($judul, $login = false) {
    $user = $_SESSION['user'] ?? null;
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($judul) ?> — Bank Sampah</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bangers&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Pirata+One&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
</head>
<body class="<?= $login ? 'login' : '' ?>">
<?php if ($login) { echo '<main class="login-main">'; return; } ?>
<header class="topbar">
    <div class="topbar-in">
        <a class="brand" href="<?= BASE_URL ?>/<?= h($user['role'] ?? 'login') ?>/dashboard.php">
            <?= sigil() ?>
            <span><b>Bank Sampah</b><small>EMANG SAMPAH</small></span>
        </a>
        <?php if ($user) { ?>
        <nav class="nav">
            <?php
            $cur = $_SERVER['SCRIPT_NAME'];
            foreach (menu_role($user['role']) as [$nama, $path]) {
                $aktif = dirname($cur) === dirname(BASE_URL . '/' . $path)
                      && explode('_', basename($cur, '.php'))[0] === explode('_', basename($path, '.php'))[0];
                echo '<a href="' . BASE_URL . '/' . $path . '"' . ($aktif ? ' class="aktif"' : '') . '>' . $nama . '</a>';
            }
            ?>
            <a class="keluar" href="<?= BASE_URL ?>/logout.php">Logout</a>
        </nav>
        <?php } ?>
    </div>
</header>
<main class="wrap">
<h2 class="judul"><?= h($judul) ?></h2>
<?php
    if (!empty($_SESSION['flash'])) {
        [$tipe, $pesan] = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert ' . ($tipe === 'ok' ? 'ok' : 'error') . '">' . h($pesan) . '</div>';
    }
}

function layout_akhir($login = false) {
    if ($login) { echo "</main>\n</body>\n</html>"; return; }
    ?>
</main>
<footer class="foot">BANK SAMPAH &bull; SETIAP SAMPAH ADA HARGANYA</footer>
</body>
</html>
<?php
}
