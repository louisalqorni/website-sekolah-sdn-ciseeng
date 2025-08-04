<?php
session_start();
require_once './inc/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_buku'])) {
    $id_buku = (int)$_POST['id_buku'];

    // Cek apakah stok masih tersedia
    $cek = mysqli_query($koneksi, "SELECT * FROM buku WHERE id = $id_buku AND stok > 0");
    if (mysqli_num_rows($cek) === 0) {
        echo "<script>alert('Buku tidak tersedia atau stok habis.'); window.location='pinjam.php';</script>";
        exit;
    }

    // Tanggal hari ini dan batas pengembalian
    $tanggal_pinjam = date('Y-m-d');
    $tanggal_kembali = date('Y-m-d', strtotime('+7 days'));

    // Simpan ke tabel peminjaman
    mysqli_query($koneksi, "
        INSERT INTO peminjaman (id_buku, peminjam, status, tgl_pinjam, tgl_kembali)
        VALUES ($id_buku, '$username', 'dipinjam', '$tanggal_pinjam', '$tanggal_kembali')
    ");

    // Kurangi stok buku
    mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id = $id_buku");

    echo "<script>alert('Peminjaman berhasil!'); window.location='dashboard.php';</script>";
    exit;
} else {
    header('Location: pinjam.php');
    exit;
}
?>
