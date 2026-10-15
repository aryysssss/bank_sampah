<?php
require_once "../config/layout.php";
wajib_role(['admin']);

// Grafik 1: stok per jenis
$label_stok = [];
$data_stok  = [];
$q = mysqli_query($koneksi, "SELECT jenis, stok_kg FROM sampah ORDER BY jenis");
while ($r = mysqli_fetch_assoc($q)) {
    $label_stok[] = $r['jenis'];
    $data_stok[]  = (float)$r['stok_kg'];
}

// Grafik 2: total per bulan
$setoran   = [];
$pembelian = [];

$q = mysqli_query($koneksi, "
    SELECT DATE_FORMAT(tanggal, '%Y-%m') AS bulan, SUM(total_rp) AS total
    FROM setoran WHERE status = 'disetujui' GROUP BY bulan");
while ($r = mysqli_fetch_assoc($q)) $setoran[$r['bulan']] = (float)$r['total'];

$q = mysqli_query($koneksi, "
    SELECT DATE_FORMAT(tanggal, '%Y-%m') AS bulan, SUM(total_rp) AS total
    FROM pembelian WHERE status = 'disetujui' GROUP BY bulan");
while ($r = mysqli_fetch_assoc($q)) $pembelian[$r['bulan']] = (float)$r['total'];

$bulan = array_unique(array_merge(array_keys($setoran), array_keys($pembelian)));
sort($bulan);

$data_setoran   = [];
$data_pembelian = [];
foreach ($bulan as $b) {
    $data_setoran[]   = $setoran[$b]   ?? 0;
    $data_pembelian[] = $pembelian[$b] ?? 0;
}

layout_awal('Grafik');
?>
<section class="panel">
    <h3>Stok Sampah per Jenis (kg)</h3>
    <div class="kanvas"><canvas id="grafikStok"></canvas></div>
</section>

<section class="panel">
    <h3>Setoran vs Pembelian per Bulan (Rp)</h3>
    <?php if (empty($bulan)) { ?>
        <p><i>Belum ada transaksi yang disetujui.</i></p>
    <?php } else { ?>
        <div class="kanvas"><canvas id="grafikBulanan"></canvas></div>
    <?php } ?>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Libre Baskerville', Georgia, serif";
Chart.defaults.color = '#0a0a0a';
const INK = '#0a0a0a', BLOOD = '#9b1111';
const opsi = {
    plugins: { legend: { labels: { font: { family: "'Bangers', Impact, sans-serif", size: 15 }, boxWidth: 18 } } },
    scales: {
        x: { grid: { color: '#0a0a0a', lineWidth: 1 }, border: { color: INK, width: 3 } },
        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.25)' }, border: { color: INK, width: 3 } }
    }
};

new Chart(document.getElementById('grafikStok'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($label_stok) ?>,
        datasets: [{ label: 'Stok (kg)', data: <?= json_encode($data_stok) ?>,
                     backgroundColor: INK, borderColor: INK, borderWidth: 3 }]
    },
    options: opsi
});

<?php if (!empty($bulan)) { ?>
new Chart(document.getElementById('grafikBulanan'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($bulan) ?>,
        datasets: [
            { label: 'Setoran (Rp)',   data: <?= json_encode($data_setoran) ?>,
              backgroundColor: INK, borderColor: INK, borderWidth: 3 },
            { label: 'Pembelian (Rp)', data: <?= json_encode($data_pembelian) ?>,
              backgroundColor: BLOOD, borderColor: INK, borderWidth: 3 }
        ]
    },
    options: opsi
});
<?php } ?>
</script>
<?php layout_akhir(); ?>
