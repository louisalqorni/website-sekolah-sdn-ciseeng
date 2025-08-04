<?php
session_start();
require_once './inc/koneksi.php';

// Cek login dan role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $stok = (int)$_POST['stok'];

    if ($judul && $penulis && $penerbit && $stok >= 0) {
        $query = "INSERT INTO buku (judul, penulis, penerbit, stok) 
                  VALUES ('$judul', '$penulis', '$penerbit', $stok)";
        if (mysqli_query($koneksi, $query)) {
            $success = "Buku berhasil ditambahkan.";
        } else {
            $error = "Gagal menambahkan buku: " . mysqli_error($koneksi);
        }
    } else {
        $error = "Semua field harus diisi dengan benar.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2 class="mb-4">📚 Tambah Buku Baru</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Judul Buku</label>
            <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Penulis</label>
            <input type="text" name="penulis" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Penerbit</label>
            <input type="text" name="penerbit" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stok</label>
            <input type="number" name="stok" class="form-control" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah Buku</button>
        <a href="dashboard_admin.php" class="btn btn-secondary">⬅️ Kembali</a>
    </form>
</body>
</html>
