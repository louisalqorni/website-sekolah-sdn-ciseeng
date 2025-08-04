<?php
session_start();
require_once './inc/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<div class='alert alert-danger mt-4 text-center'>Data profil tidak ditemukan. Pastikan username <strong>$username</strong> ada di tabel <strong>user</strong>.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Profil Saya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" />
  <style>
    body {
      background: #f8f9fa;
    }
    .profile-card {
      max-width: 600px;
      margin: 40px auto;
    }
    .profile-header {
      background: #0d6efd;
      color: white;
      padding: 20px;
      border-radius: 0.375rem 0.375rem 0 0;
      text-align: center;
    }
    .profile-content {
      padding: 30px;
      background: white;
      border-radius: 0 0 0.375rem 0.375rem;
    }
    .profile-content th {
      width: 150px;
      font-weight: 600;
      color: #555;
    }
    .btn-back {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="profile-card shadow">
    <div class="profile-header">
      <h2><i class="bi bi-person-circle"></i> Profil Saya</h2>
    </div>
    <div class="profile-content">
      <table class="table table-borderless">
        <tbody>
          <tr>
            <th>Nama Lengkap</th>
            <td><?= htmlspecialchars($data['nama_lengkap']) ?></td>
          </tr>
          <tr>
            <th>Username</th>
            <td><?= htmlspecialchars($data['username']) ?></td>
          </tr>
          <tr>
            <th>NIS</th>
            <td><?= htmlspecialchars($data['nis']) ?></td>
          </tr>
        </tbody>
      </table>
      <a href="dashboard_siswa.php" class="btn btn-outline-primary btn-back"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
