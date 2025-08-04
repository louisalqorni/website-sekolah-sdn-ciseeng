<?php
session_start();
require_once './inc/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$tanggal_hari_ini = date('d-m-Y');

// Ambil data buku dengan stok > 0
$query_buku = mysqli_query($koneksi, "SELECT * FROM buku WHERE stok > 0");

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku = (int)$_POST['id_buku'];
    $kondisi = $_POST['kondisi'];
    $tgl_pinjam = date('Y-m-d');
    $tgl_kembali = date('Y-m-d', strtotime('+7 days'));

    mysqli_query($koneksi, "INSERT INTO peminjaman (id_buku, peminjam, tgl_pinjam, tgl_kembali, kondisi_pinjam, status) 
                            VALUES ($id_buku, '$username', '$tgl_pinjam', '$tgl_kembali', '$kondisi', 'dipinjam')");
    mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id = $id_buku");

    header('Location: pinjam.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Formulir Peminjaman Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
        }
        .tab-button {
            border-bottom: 3px solid transparent;
        }
        .tab-button.active {
            border-color: #0d6efd;
            font-weight: bold;
        }
        .card {
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <!-- Baris atas: judul & tombol logout -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Peminjaman Buku</h4>
        <div class="text-muted"><?= date('l, d F Y') ?></div>
        <a href="dashboard_siswa.php" class="btn btn-danger btn-sm">🔒 Keluar</a>
    </div>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active tab-button" href="#">Formulir Peminjaman Buku</a>
        </li>
    </ul>

    <div class="card shadow-sm p-4">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Anggota</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($username) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Judul Buku</label>
                <select name="id_buku" class="form-select" required>
                    <option value="">-- Silahkan pilih buku yang akan di pinjam --</option>
                    <?php while ($b = mysqli_fetch_assoc($query_buku)) : ?>
                        <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['judul']) ?> (Stok: <?= $b['stok'] ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Peminjaman</label>
                <input type="text" class="form-control" value="<?= $tanggal_hari_ini ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Kondisi Buku Saat Dipinjam</label>
                <select name="kondisi" class="form-select" required>
                    <option value="">-- Silahkan pilih kondisi buku saat dipinjam --</option>
                    <option value="baik">Baik</option>
                    <option value="rusak ringan">Rusak Ringan</option>
                    <option value="rusak berat">Rusak Berat</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Kirim</button>
        </form>
    </div>
</div>
</body>
</html>
