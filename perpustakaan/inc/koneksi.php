<?php
$host = 'localhost';    // server database
$user = 'root';         // user db, sesuaikan
$pass = '';             // password db, sesuaikan
$dbname = 'db_perpus_sekolah'; // nama database

$koneksi = mysqli_connect($host, $user, $pass, $dbname);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
