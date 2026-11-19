<!--
Nama    : Amanda Lisa Asriningsih
NIM     : 250202001
Kelas   : TI-2A
-->
<?php 
//koneksi.php - Menghubungkan PHP dengan database MySQL (MYSQLi procedural)

//PHP 8.1+ secara bawaan melempar exception bila terjadi kesalahan koneksi.
//Baris ini memastikan agar kesalahan dapat diperiksa dengan if
mysqli_report(MYSQLI_REPORT_OFF);

//parameter koneksi
$host       = "localhost";
$username   = "root";
$password   = "";
$database   = "db_perpustakaan";

//Membuat koneksi
$koneksi = mysqli_connect($host, $username, $password, $database);

//Memeriksa koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . htmlspecialchars(mysqli_connect_error()));
}

//Mengatur karakter agar huruf dan simbol tersimpan dengan benar
mysqli_set_charset($koneksi, "utf8mb4");
?>