<?php
// Semua error MySQL dilempar sebagai exception supaya bisa ditangkap try/catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $koneksi = mysqli_connect("localhost", "root", "", "emang_sampah");
    mysqli_set_charset($koneksi, "utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Koneksi database gagal. Pastikan MySQL di XAMPP menyala dan database 'emang_sampah' sudah diimport.");
}
