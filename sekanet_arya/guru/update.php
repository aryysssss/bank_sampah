```php
<?php
include "koneksi.php";

$nip_lama = $_POST['nip_lama'];
$nip       = $_POST['nip'];
$nama      = $_POST['nama'];
$alamat    = $_POST['alamat'];

$query = mysqli_query($koneksi, "
    UPDATE guru SET
        nip='$nip',
        nama='$nama',
        alamat='$alamat'
    WHERE nip='$nip_lama'
");

if ($query) {
    echo "<script>
            alert('Data guru berhasil diupdate!');
            window.location='?page=guru/index';
          </script>";
} else {
    echo "<script>
            alert('Data guru gagal diupdate!');
            window.location='?page=guru/index';
          </script>";
}
?>
```
