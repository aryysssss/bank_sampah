<?php
session_start();

include "koneksi.php";

$pesan = "";

if(isset($_POST['login'])){

    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);


    $query = mysqli_query($koneksi,
    "SELECT * FROM users 
    WHERE username='$username' 
    AND password='$password'");


    $cek = mysqli_num_rows($query);


    if($cek > 0){

        $data = mysqli_fetch_array($query);

        $_SESSION['status'] = "sukses";
        $_SESSION['username'] = $data['username'];

        header("location:index.php");
        exit;

    }else{

        $pesan = "Username atau password salah!";

    }

}

?>


<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login Sistem Sekolah</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<style>

body{

    background:linear-gradient(135deg,#2e7d32,#81c784);

    height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    font-family:'Segoe UI',sans-serif;

}


.login-card{

    width:380px;

    background:white;

    padding:35px;

    border-radius:20px;

    box-shadow:0 10px 25px rgba(0,0,0,.2);

}


.logo{

    text-align:center;

    color:#2e7d32;

    font-size:50px;

}


h3{

    text-align:center;

    font-weight:bold;

    color:#2e7d32;

}


.btn-login{

    background:#2e7d32;

    color:white;

    width:100%;

}


.btn-login:hover{

    background:#1b5e20;

    color:white;

}


</style>


</head>


<body>


<div class="login-card">


<div class="logo">
<i class="bi bi-mortarboard-fill"></i>
</div>


<h3>
Login Sistem Sekolah
</h3>


<br>


<?php if($pesan!=""){ ?>

<div class="alert alert-danger">
<?= $pesan; ?>
</div>

<?php } ?>



<form method="POST">


<div class="mb-3">

<label class="form-label">
Username
</label>

<input type="text"
name="username"
class="form-control"
placeholder="Masukkan username"
required>

</div>



<div class="mb-3">

<label class="form-label">
Password
</label>

<input type="password"
name="password"
class="form-control"
placeholder="Masukkan password"
required>

</div>



<button type="submit"
name="login"
class="btn btn-login">

Login

</button>


</form>


</div>


</body>

</html>