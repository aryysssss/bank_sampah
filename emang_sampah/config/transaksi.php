<?php
/*
 * Fungsi bersama untuk setoran, pembelian, dan penarikan.
 * Semua perubahan saldo dan stok lewat file ini supaya aturannya ada di satu tempat.
 */
require_once __DIR__ . "/layout.php";

/* ---------- Data tiap jenis transaksi ---------- */
function trx_meta($jenis) {
    static $semua = [
        'setoran' => [
            'tabel' => 'setoran', 'kunci' => 'id_setoran', 'halaman' => 'setoran',
            'label' => 'Setoran', 'kolom' => 'berat_kg', 'judul_input' => 'Berat (kg)',
            'detail' => "SELECT x.*, u.nama AS pihak, sm.jenis AS nama_sampah
                         FROM setoran x
                         JOIN users u   ON x.id_user = u.id_user
                         JOIN sampah sm ON x.id_sampah = sm.id_sampah
                         WHERE x.id_setoran = ?",
            'pesan' => [
                'disetujui' => "Setoran disetujui. Saldo nasabah dan stok bertambah, saldo admin berkurang %s.",
                'ditolak'   => "Setoran ditolak.",
            ],
        ],
        'pembelian' => [
            'tabel' => 'pembelian', 'kunci' => 'id_beli', 'halaman' => 'pembelian',
            'label' => 'Pembelian', 'kolom' => 'berat_kg', 'judul_input' => 'Berat (kg)',
            'detail' => "SELECT x.*, u.nama AS pihak, sm.jenis AS nama_sampah
                         FROM pembelian x
                         JOIN users u   ON x.id_pengepul = u.id_user
                         JOIN sampah sm ON x.id_sampah = sm.id_sampah
                         WHERE x.id_beli = ?",
            'pesan' => [
                'disetujui' => "Pembelian disetujui. Stok berkurang dan saldo admin bertambah %s.",
                'ditolak'   => "Pembelian ditolak. Saldo pengepul dikembalikan %s.",
            ],
        ],
        'penarikan' => [
            'tabel' => 'penarikan', 'kunci' => 'id_tarik', 'halaman' => 'tarik',
            'label' => 'Penarikan', 'kolom' => 'jumlah', 'judul_input' => 'Jumlah (Rp)',
            'detail' => "SELECT x.*, u.nama AS pihak
                         FROM penarikan x
                         JOIN users u ON x.id_user = u.id_user
                         WHERE x.id_tarik = ?",
            'pesan' => [
                'disetujui' => "Penarikan disetujui. Saldo nasabah dipotong %s.",
                'ditolak'   => "Penarikan ditolak.",
            ],
        ],
    ];
    if (!isset($semua[$jenis])) throw new InvalidArgumentException("Jenis transaksi tidak dikenal.");
    return $semua[$jenis];
}

/* ---------- Query singkat ---------- */
function jalankan($koneksi, $sql, $tipe = "", array $nilai = []) {
    $stmt = mysqli_prepare($koneksi, $sql);
    if ($nilai) mysqli_stmt_bind_param($stmt, $tipe, ...$nilai);
    mysqli_stmt_execute($stmt);
    return $stmt;
}

function satu_baris($stmt) {
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
}

/* ---------- Saldo dan stok (tidak boleh minus) ---------- */
function ubah_saldo($koneksi, $id_user, $delta) {
    $delta = round((float)$delta, 2);
    if ($delta == 0.0) return;

    $u = satu_baris(jalankan($koneksi,
        "SELECT nama, saldo FROM users WHERE id_user = ? FOR UPDATE", "i", [(int)$id_user]));
    if (!$u) throw new Exception("Akun tidak ditemukan.");
    if ((float)$u['saldo'] + $delta < -0.004) {
        throw new Exception("Saldo {$u['nama']} tidak cukup (saldo sekarang " . rp($u['saldo']) . ").");
    }
    jalankan($koneksi, "UPDATE users SET saldo = saldo + ? WHERE id_user = ?", "di", [$delta, (int)$id_user]);
}

function ubah_stok($koneksi, $id_sampah, $delta) {
    $delta = round((float)$delta, 2);
    if ($delta == 0.0) return;

    $s = satu_baris(jalankan($koneksi,
        "SELECT jenis, stok_kg FROM sampah WHERE id_sampah = ? FOR UPDATE", "i", [(int)$id_sampah]));
    if (!$s) throw new Exception("Jenis sampah tidak ditemukan.");
    if ((float)$s['stok_kg'] + $delta < -0.004) {
        throw new Exception("Stok {$s['jenis']} tidak cukup (stok sekarang " . kg($s['stok_kg']) . ").");
    }
    jalankan($koneksi, "UPDATE sampah SET stok_kg = stok_kg + ? WHERE id_sampah = ?", "di", [$delta, (int)$id_sampah]);
}

/*
 * Efek sebuah transaksi pada saldo dan stok, menurut statusnya.
 * $arah = 1 menerapkan efek, $arah = -1 membatalkannya.
 *
 *  setoran   disetujui : saldo nasabah +, stok +, saldo admin -
 *  pembelian pending / disetujui : saldo pengepul - (dipotong saat mengajukan)
 *  pembelian disetujui : stok -, saldo admin +
 *  penarikan disetujui : saldo nasabah -
 */
function efek_transaksi($koneksi, $jenis, array $r, $arah) {
    $admin  = (int)$_SESSION['user']['id_user'];
    $status = $r['status'];

    if ($jenis === 'setoran' && $status === 'disetujui') {
        ubah_saldo($koneksi, $r['id_user'],    $arah * $r['total_rp']);
        ubah_stok($koneksi,  $r['id_sampah'],  $arah * $r['berat_kg']);
        ubah_saldo($koneksi, $admin,          -$arah * $r['total_rp']);
    }

    if ($jenis === 'pembelian') {
        if ($status === 'pending' || $status === 'disetujui') {
            ubah_saldo($koneksi, $r['id_pengepul'], -$arah * $r['total_rp']);
        }
        if ($status === 'disetujui') {
            ubah_stok($koneksi,  $r['id_sampah'], -$arah * $r['berat_kg']);
            ubah_saldo($koneksi, $admin,           $arah * $r['total_rp']);
        }
    }

    if ($jenis === 'penarikan' && $status === 'disetujui') {
        ubah_saldo($koneksi, $r['id_user'], -$arah * $r['jumlah']);
    }
}

function ambil_transaksi($koneksi, $jenis, $id, $status = null) {
    $m    = trx_meta($jenis);
    $sql  = "SELECT * FROM {$m['tabel']} WHERE {$m['kunci']} = ?";
    $tipe = "i";
    $isi  = [(int)$id];
    if ($status !== null) { $sql .= " AND status = ?"; $tipe .= "s"; $isi[] = $status; }
    $sql .= " FOR UPDATE";

    $r = satu_baris(jalankan($koneksi, $sql, $tipe, $isi));
    if (!$r) {
        throw new Exception($status !== null
            ? "{$m['label']} tidak ditemukan atau sudah diproses."
            : "{$m['label']} tidak ditemukan.");
    }
    return $r;
}

/* ---------- Aksi: setujui / tolak ---------- */
function proses_persetujuan($jenis) {
    global $koneksi;
    csrf_cek();

    $m    = trx_meta($jenis);
    $id   = (int)($_POST['id'] ?? 0);
    $baru = ['setuju' => 'disetujui', 'tolak' => 'ditolak'][$_POST['aksi'] ?? ''] ?? null;

    mysqli_begin_transaction($koneksi);
    try {
        if ($baru === null) throw new Exception("Aksi tidak dikenal.");

        $r = ambil_transaksi($koneksi, $jenis, $id, 'pending');

        // batalkan efek status lama, lalu terapkan efek status baru
        efek_transaksi($koneksi, $jenis, $r, -1);
        $r['status'] = $baru;
        efek_transaksi($koneksi, $jenis, $r, 1);

        jalankan($koneksi,
            "UPDATE {$m['tabel']} SET status = ?, tanggal_proses = NOW() WHERE {$m['kunci']} = ?",
            "si", [$baru, $id]);

        mysqli_commit($koneksi);
        flash('ok', sprintf($m['pesan'][$baru], rp($r['total_rp'] ?? $r['jumlah'])));
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        flash('error', $e->getMessage());
    }
    pindah($m['halaman'] . ".php");
}

/* ---------- Aksi: hapus (efek saldo/stok dibatalkan dulu) ---------- */
function hapus_transaksi($jenis) {
    global $koneksi;
    csrf_cek();

    $m  = trx_meta($jenis);
    $id = (int)($_POST['id'] ?? 0);

    mysqli_begin_transaction($koneksi);
    try {
        $r = ambil_transaksi($koneksi, $jenis, $id);
        efek_transaksi($koneksi, $jenis, $r, -1);
        jalankan($koneksi, "DELETE FROM {$m['tabel']} WHERE {$m['kunci']} = ?", "i", [$id]);

        mysqli_commit($koneksi);
        flash('ok', "{$m['label']} dihapus. Saldo dan stok sudah disesuaikan kembali.");
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        flash('error', "Tidak bisa dihapus: " . $e->getMessage());
    }
    pindah($m['halaman'] . ".php");
}

/* ---------- Halaman edit (berat atau jumlah) ---------- */
function halaman_edit_transaksi($jenis) {
    global $koneksi;
    csrf_cek();

    $m      = trx_meta($jenis);
    $id     = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
    $detail = satu_baris(jalankan($koneksi, $m['detail'], "i", [$id]));

    if (!$detail) {
        flash('error', "{$m['label']} tidak ditemukan!");
        pindah($m['halaman'] . ".php");
    }

    $pesan = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nilai = $_POST['nilai'] ?? '';

        if (!is_numeric($nilai) || round((float)$nilai, 2) <= 0) {
            $pesan = "Nilai tidak valid!";
        } else {
            $nilai = round((float)$nilai, 2);

            mysqli_begin_transaction($koneksi);
            try {
                $r = ambil_transaksi($koneksi, $jenis, $id);
                efek_transaksi($koneksi, $jenis, $r, -1);          // batalkan efek lama

                if ($m['kolom'] === 'jumlah') {
                    $r['jumlah'] = $nilai;
                    jalankan($koneksi, "UPDATE penarikan SET jumlah = ? WHERE id_tarik = ?", "di", [$nilai, $id]);
                } else {
                    // total dihitung ulang dengan harga per kg saat transaksi dibuat
                    $harga = (float)$r['berat_kg'] > 0 ? $r['total_rp'] / $r['berat_kg'] : 0;
                    $r['berat_kg'] = $nilai;
                    $r['total_rp'] = round($harga * $nilai, 2);
                    jalankan($koneksi,
                        "UPDATE {$m['tabel']} SET berat_kg = ?, total_rp = ? WHERE {$m['kunci']} = ?",
                        "ddi", [$r['berat_kg'], $r['total_rp'], $id]);
                }

                efek_transaksi($koneksi, $jenis, $r, 1);           // terapkan efek baru

                mysqli_commit($koneksi);
                flash('ok', "{$m['label']} diperbarui. Saldo dan stok sudah disesuaikan.");
                pindah($m['halaman'] . ".php");
            } catch (Exception $e) {
                mysqli_rollback($koneksi);
                $pesan = $e->getMessage();
            }
        }
    }

    layout_awal('Edit ' . $m['label']);
    if ($pesan) echo '<div class="alert error">' . h($pesan) . '</div>';
    ?>
<section class="panel lebar-form">
    <h3>Ubah Data</h3>
    <form method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int)$id ?>">
        <p>Pihak: <b><?= h($detail['pihak']) ?></b></p>
        <?php if (isset($detail['nama_sampah'])) { ?>
        <p>Jenis sampah: <b><?= h($detail['nama_sampah']) ?></b></p>
        <?php } ?>
        <p>Status: <?= badge($detail['status']) ?></p>
        <label for="nilai"><?= h($m['judul_input']) ?></label>
        <input type="number" id="nilai" name="nilai" step="0.01" min="0.01"
               value="<?= h($detail[$m['kolom']]) ?>" required>
        <p class="catatan">Saldo dan stok ikut disesuaikan otomatis sesuai status transaksi.</p>
        <div class="aksi" style="margin-top:16px">
            <button type="submit">Simpan</button>
            <a class="btn terang" href="<?= h($m['halaman']) ?>.php">Batal</a>
        </div>
    </form>
</section>
<?php
    layout_akhir();
}

/* ---------- Tombol Edit + Hapus di tabel ---------- */
function tombol_ubah($jenis, $id) {
    $h = trx_meta($jenis)['halaman'];
    return '<a class="btn terang" href="' . $h . '_edit.php?id=' . (int)$id . '">Edit</a>'
         . '<form action="' . $h . '_hapus.php" method="POST" class="inline"'
         . ' onsubmit="return confirm(\'Hapus data ini? Saldo dan stok akan disesuaikan kembali.\')">'
         . csrf_field()
         . '<input type="hidden" name="id" value="' . (int)$id . '">'
         . '<button type="submit" class="bahaya">Hapus</button></form>';
}
