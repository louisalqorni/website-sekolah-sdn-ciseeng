<?php
session_start();
require_once './inc/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

$pinjamAktif = mysqli_num_rows(mysqli_query($koneksi, "
    SELECT * FROM peminjaman WHERE peminjam='$username' AND status='dipinjam'
"));

$dendaBelum = mysqli_num_rows(mysqli_query($koneksi, "
    SELECT d.id FROM denda d
    JOIN peminjaman p ON d.id_peminjaman = p.id
    WHERE p.peminjam='$username' AND d.status='belum dibayar'
"));

$terlambat = mysqli_num_rows(mysqli_query($koneksi, "
    SELECT * FROM peminjaman WHERE peminjam='$username' AND status='dipinjam' AND tgl_kembali < CURDATE()
"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" />
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #343a40;
      padding: 20px;
      color: white;
    }
    .sidebar h4 {
      color: white;
      margin-bottom: 30px;
    }
    .sidebar a {
      display: block;
      color: #ccc;
      padding: 10px;
      margin-bottom: 10px;
      text-decoration: none;
      border-radius: 6px;
    }
    .sidebar a.active,
    .sidebar a:hover {
      background-color: #495057;
      color: white;
    }
    .logout-btn {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 10px;
      width: 100%;
      margin-top: auto;
      border-radius: 5px;
    }
  </style>
</head>
<body>

<div class="d-flex">

  <!-- Sidebar -->
<div class="sidebar d-flex flex-column">
  <h4>Dashboard Siswa</h4>
  <a href="buku.php"><i class="bi bi-book"></i> Lihat Buku</a>
  <a href="pinjam.php"><i class="bi bi-plus-circle"></i> Pinjam Buku</a>
  <a href="pesan.php"><i class="bi bi-envelope"></i> Pesan</a>
  <a href="profil.php"><i class="bi bi-person"></i> Profil Saya</a> <!-- Tambahan -->
  <form action="logout.php" method="post" class="mt-auto">
    <button class="logout-btn" type="submit">Logout</button>
  </form>
</div>


  <!-- Main Content -->
  <div class="flex-grow-1 p-4">
    <h3>📚 Selamat Datang, <?= htmlspecialchars($username) ?></h3>

    <?php if ($terlambat > 0): ?>
      <div class="alert alert-warning mt-3">⚠️ Anda memiliki <?= $terlambat ?> pinjaman yang melewati batas waktu!</div>
    <?php endif; ?>

    <div class="row mt-4">
      <div class="col-md-4">
        <div class="card text-bg-primary mb-3">
          <div class="card-body">
            <h5 class="card-title">Pinjaman Aktif</h5>
            <p class="card-text fs-1"><?= $pinjamAktif ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-bg-danger mb-3">
          <div class="card-body">
            <h5 class="card-title">Denda Belum Lunas</h5>
            <p class="card-text fs-1"><?= $dendaBelum ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
