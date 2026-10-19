<?php
session_start();

if ($_SESSION['status'] != "sukses") {
    header("location:logout.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Sistem Penilaian Sekolah</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{
    background:#f1f5f9;
}

.navbar{
    background:#16a085;
}

.navbar-brand{
    color:white !important;
    font-weight:bold;
}

.sidebar{
    min-height:100vh;
    background:#0f766e;
}

.sidebar a{
    color:white;
    text-decoration:none;
    display:block;
    padding:12px 15px;
    border-radius:8px;
    margin-bottom:8px;
}

.sidebar a:hover{
    background:#14b8a6;
}

.content{
    padding:25px;
}
</style>

</head>
<body>

<nav class="navbar navbar-dark">
<div class="container-fluid">
<a class="navbar-brand" href="index.php">
<i class="bi bi-mortarboard-fill"></i>
Sistem Penilaian Sekolah
</a>

<a href="logout.php" class="btn btn-danger">
Logout
</a>
</div>
</nav>

<div class="container-fluid">
<div class="row">

<div class="col-md-2 sidebar p-3">

<h5 class="text-white mb-3">MENU</h5>

<a href="index.php">
<i class="bi bi-house-fill"></i>
Beranda
</a>

<a href="?page=siswa/index">
<i class="bi bi-people-fill"></i>
Data Siswa
</a>

<a href="?page=guru/index">
<i class="bi bi-person-badge-fill"></i>
Data Guru
</a>

<a href="?page=mapel/index">
<i class="bi bi-book-fill"></i>
Mata Pelajaran
</a>

<a href="?page=nilai/index">
<i class="bi bi-award-fill"></i>
Data Nilai
</a>

</div>

<div class="col-md-10 content">

<?php
include "isi.php";
?>

</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>