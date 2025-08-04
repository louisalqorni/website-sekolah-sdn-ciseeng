<?php
session_start();
require_once './inc/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit;
}

$id_pengirim = $_SESSION['id_user'];

$admin_result = mysqli_query($koneksi, "SELECT id, username FROM users WHERE role = 'admin'");

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_penerima = (int)$_POST['id_penerima'];
    $isi_pesan = mysqli_real_escape_string($koneksi, $_POST['isi_pesan']);

    if (empty(trim($isi_pesan))) {
        $error = "Pesan tidak boleh kosong.";
    } else {
        $query = "INSERT INTO pesan (id_pengirim, id_penerima, isi_pesan, waktu_kirim) 
                  VALUES ($id_pengirim, $id_penerima, '$isi_pesan', NOW())";

        if (mysqli_query($koneksi, $query)) {
            $success = "Pesan berhasil dikirim.";
        } else {
            $error = "Gagal mengirim pesan: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Kirim Pesan ke Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container py-4">
    <h2>✉️ Kirim Pesan ke Admin</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="id_penerima" class="form-label">Pilih Admin Penerima:</label>
            <select name="id_penerima" id="id_penerima" class="form-select" required>
                <?php while($admin = mysqli_fetch_assoc($admin_result)): ?>
                    <option value="<?= $admin['id'] ?>"><?= htmlspecialchars($admin['username']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="isi_pesan" class="form-label">Pesan:</label>
            <textarea id="isi_pesan" name="isi_pesan" rows="5" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
        <a href="dashboard_siswa.php" class="btn btn-secondary">⬅️ Kembali</a>
    </form>
</body>
</html>
