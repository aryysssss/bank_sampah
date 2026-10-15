<?php
require_once "../config/layout.php";
wajib_role(['admin']);

$dari   = $_GET['dari']   ?? '';
$sampai = $_GET['sampai'] ?? '';
$status = $_GET['status'] ?? '';
$tipe   = $_GET['tipe']   ?? '';

if (!in_array($status, ['pending', 'disetujui', 'ditolak'], true)) $status = '';
if (!in_array($tipe, ['Setoran', 'Pembelian'], true)) $tipe = '';

$sql = "SELECT * FROM (
    SELECT 'Setoran' AS tipe, s.tanggal, u.nama AS pihak, sm.jenis,
           s.berat_kg, s.total_rp, s.status
    FROM setoran s
    JOIN users u   ON s.id_user = u.id_user
    JOIN sampah sm ON s.id_sampah = sm.id_sampah
    UNION ALL
    SELECT 'Pembelian', p.tanggal, u.nama, sm.jenis,
           p.berat_kg, p.total_rp, p.status
    FROM pembelian p
    JOIN users u   ON p.id_pengepul = u.id_user
    JOIN sampah sm ON p.id_sampah = sm.id_sampah
) t WHERE 1=1";

$types  = "";
$params = [];

if ($dari !== '')   { $sql .= " AND DATE(tanggal) >= ?"; $types .= "s"; $params[] = $dari; }
if ($sampai !== '') { $sql .= " AND DATE(tanggal) <= ?"; $types .= "s"; $params[] = $sampai; }
if ($status !== '') { $sql .= " AND status = ?";         $types .= "s"; $params[] = $status; }
if ($tipe !== '')   { $sql .= " AND tipe = ?";           $types .= "s"; $params[] = $tipe; }

$sql .= " ORDER BY tanggal DESC";

$stmt = mysqli_prepare($koneksi, $sql);
if ($params) mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

$total_setor = 0;
$total_beli  = 0;

layout_awal('Transaksi');
?>
<section class="panel">
    <h3>Filter</h3>
    <form method="GET" class="filter">
        <div><label for="dari">Dari</label>
            <input type="date" id="dari" name="dari" value="<?= h($dari) ?>"></div>
        <div><label for="sampai">Sampai</label>
            <input type="date" id="sampai" name="sampai" value="<?= h($sampai) ?>"></div>
        <div><label for="tipe">Tipe</label>
            <select id="tipe" name="tipe">
                <option value="">Semua</option>
                <option value="Setoran"   <?= $tipe === 'Setoran' ? 'selected' : '' ?>>Setoran</option>
                <option value="Pembelian" <?= $tipe === 'Pembelian' ? 'selected' : '' ?>>Pembelian</option>
            </select></div>
        <div><label for="status">Status</label>
            <select id="status" name="status">
                <option value="">Semua</option>
                <option value="pending"   <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="disetujui" <?= $status === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                <option value="ditolak"   <?= $status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
            </select></div>
        <div class="tombol">
            <button type="submit">Filter</button>
            <a class="btn terang" href="transaksi.php">Reset</a>
        </div>
    </form>
</section>

<section class="panel">
    <h3>Daftar Transaksi</h3>
    <div class="tabel-wrap">
    <table>
        <thead><tr>
            <th>Tanggal</th><th>Tipe</th><th>Pihak</th><th>Jenis</th>
            <th class="angka">Berat</th><th class="angka">Total</th><th>Status</th>
        </tr></thead>
        <tbody>
        <?php $ada = false; while ($r = mysqli_fetch_assoc($hasil)) {
            $ada = true;
            if ($r['status'] === 'disetujui') {
                if ($r['tipe'] === 'Setoran') $total_setor += $r['total_rp'];
                else                          $total_beli  += $r['total_rp'];
            }
        ?>
        <tr>
            <td><?= tgl($r['tanggal']) ?></td>
            <td><?= h($r['tipe']) ?></td>
            <td><?= h($r['pihak']) ?></td>
            <td><?= h($r['jenis']) ?></td>
            <td class="angka"><?= kg($r['berat_kg']) ?></td>
            <td class="angka"><?= rp($r['total_rp']) ?></td>
            <td><?= badge($r['status']) ?></td>
        </tr>
        <?php } if (!$ada) echo '<tr><td colspan="7" class="kosong">Tidak ada transaksi yang cocok.</td></tr>'; ?>
        </tbody>
    </table>
    </div>
    <p style="margin-top:16px">
        Total setoran disetujui: <b><?= rp($total_setor) ?></b><br>
        Total pembelian disetujui: <b><?= rp($total_beli) ?></b>
    </p>
    <p class="catatan">Total hanya menghitung transaksi berstatus disetujui sesuai filter di atas.</p>
</section>
<?php layout_akhir(); ?>
