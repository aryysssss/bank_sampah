<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/koneksi.php";

/*
 * Deteksi alamat dasar aplikasi secara otomatis.
 * Mendukung aplikasi yang diunggah langsung ke public_html/htdocs
 * maupun ke dalam subfolder seperti /emang_sampah.
 */
if (!defined('BASE_URL')) {
    $projectRootPath = realpath(dirname(__DIR__));
    $documentRootPath = isset($_SERVER['DOCUMENT_ROOT'])
        ? realpath($_SERVER['DOCUMENT_ROOT'])
        : false;
    $baseUrl = null;

    if ($projectRootPath !== false && $documentRootPath !== false) {
        $projectPath = str_replace('\\', '/', $projectRootPath);
        $documentPath = rtrim(str_replace('\\', '/', $documentRootPath), '/');

        if ($projectPath === $documentPath) {
            $baseUrl = '';
        } elseif (strpos($projectPath, $documentPath . '/') === 0) {
            $baseUrl = substr($projectPath, strlen($documentPath));
        }
    }

    // Fallback bila DOCUMENT_ROOT tidak dapat dipetakan ke folder project.
    if ($baseUrl === null) {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        $scriptDir = rtrim($scriptDir, '/');
        $segments = explode('/', trim($scriptDir, '/'));
        $lastSegment = end($segments);

        if (in_array($lastSegment, ['admin', 'nasabah', 'pengepul'], true)) {
            array_pop($segments);
            $scriptDir = '/' . implode('/', array_filter($segments, 'strlen'));
        }

        $baseUrl = ($scriptDir === '.' || $scriptDir === '/') ? '' : $scriptDir;
    }

    define('BASE_URL', rtrim($baseUrl, '/'));
}

function wajib_role(array $roles) {
    if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $roles, true)) {
        header("Location: " . BASE_URL . "/login.php");
        exit;
    }
}

function pindah($url) {
    header("Location: $url");
    exit;
}

/* ---- Pesan sekali tampil (pengganti alert + window.location) ---- */
function flash($tipe, $pesan) {
    $_SESSION['flash'] = [$tipe, $pesan];   // $tipe: 'ok' atau 'error'
}

/* ---- Proteksi CSRF untuk semua form POST ---- */
function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}
function csrf_cek() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(403);
        die("Permintaan tidak valid (token keamanan salah). Kembali, muat ulang halaman, lalu coba lagi.");
    }
}
