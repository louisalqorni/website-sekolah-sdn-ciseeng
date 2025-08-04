<?php
session_start();
require './inc/koneksi.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Pastikan ada ID pesan yang dikirimkan melalui GET
if (isset($_GET['id_pesan'])) {
    $id_pesan = mysqli_real_escape_string($koneksi, $_GET['id_pesan']);

    // Query untuk menghapus pesan
    $sql_delete = "DELETE FROM pesan WHERE id = '$id_pesan'";

    if (mysqli_query($koneksi, $sql_delete)) {
        // Redirect kembali ke dashboard dengan pesan sukses
        header('Location: dashboard_admin.php?pesan=hapus_pesan_sukses#pesan');
        exit;
    } else {
        // Redirect kembali ke dashboard dengan pesan gagal
        header('Location: dashboard_admin.php?pesan=hapus_pesan_gagal#pesan');
        exit;
    }
} else {
    // Jika tidak ada ID pesan, redirect kembali ke dashboard
    header('Location: dashboard_admin.php?pesan=id_pesan_tidak_ditemukan#pesan');
    exit;
}
?>