<?php
session_start();
require_once './inc/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM buku"); // Pastikan nama tabel benar
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Buku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2 class="mb-4">📖 Daftar Buku</h2>

  <table class="table table-bordered">
    <thead class="table-primary">
      <tr>
        <th>Judul</th>
        <th>Penulis</th>
        <th>Penerbit</th>
      </tr>
    </thead>
    <tbody>
      <?php while($b = mysqli_fetch_assoc($query)): ?>
      <tr>
        <td><?= htmlspecialchars($b['judul']) ?></td>
        <td><?= htmlspecialchars($b['penulis']) ?></td>
        <td><?= htmlspecialchars($b['penerbit']) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="dashboard_siswa.php" class="btn btn-secondary">⬅️ Kembali</a>
</div>
</body>
</html>
