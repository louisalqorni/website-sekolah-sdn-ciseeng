<?php
session_start();
require_once '../inc/koneksi.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role']; // 'admin' or 'siswa'

function rupiah($angka){
    return "Rp " . number_format($angka,0,',','.');
}

// Ambil data denda
if ($role === 'admin') {
    $sql = "SELECT d.*, p.peminjam, b.judul 
            FROM denda d 
            JOIN peminjaman p ON d.id_peminjaman = p.id
            JOIN buku b ON p.id_buku = b.id";
} else {
    $sql = "SELECT d.*, p.peminjam, b.judul 
            FROM denda d 
            JOIN peminjaman p ON d.id_peminjaman = p.id
            JOIN buku b ON p.id_buku = b.id
            WHERE p.peminjam = '$username'";
}
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Denda Perpustakaan</title>
  <link rel="stylesheet" href="../assets/css/anim.css">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
  <h2 class="mb-3 anim-fade"><?php echo ($role==='admin'?'Kelola':'Daftar'); ?> Denda</h2>
  <table class="table table-striped table-hover anim-slide">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Peminjam</th>
        <th>Buku</th>
        <th>Jumlah</th>
        <th>Status</th>
        <?php if($role==='admin'): ?><th>Aksi</th><?php endif; ?>
      </tr>
    </thead>
    <tbody>
    <?php $i=1; while($row=mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= $i++; ?></td>
        <td><?= htmlspecialchars($row['peminjam']); ?></td>
        <td><?= htmlspecialchars($row['judul']); ?></td>
        <td><?= rupiah($row['jumlah']); ?></td>
        <td><?= $row['status']; ?></td>
        <?php if($role==='admin'): ?>
        <td>
          <?php if($row['status']=='belum dibayar'): ?>
            <a href="proses_denda.php?id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Tandai Lunas</a>
          <?php else: ?>
            <span class="badge bg-success">Lunas</span>
          <?php endif; ?>
        </td>
        <?php endif; ?>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
