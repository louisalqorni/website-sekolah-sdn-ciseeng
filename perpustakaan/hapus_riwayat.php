<?php
session_start();
require './inc/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id_peminjaman'])) {
    header('Location: dashboard_admin.php?pesan=hapus_gagal');
    exit;
}

$id_peminjaman = intval($_GET['id_peminjaman']);

// Hapus terlebih dahulu jika ada data pengembalian yang terkait
mysqli_query($koneksi, "DELETE FROM pengembalian WHERE id_peminjaman = $id_peminjaman");

// Lalu hapus data peminjaman
$delete = mysqli_query($koneksi, "DELETE FROM peminjaman WHERE id = $id_peminjaman");

if ($delete) {
    header('Location: dashboard_admin.php?pesan=hapus_sukses#riwayat');
} else {
    header('Location: dashboard_admin.php?pesan=hapus_gagal#riwayat');
}
exit;
?>
