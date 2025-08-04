<?php
session_start();
require './inc/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);
    $query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if ($password === $user['password']) {
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: dashboard_admin.php');
                exit;
            } elseif ($user['role'] === 'siswa') {
                header('Location: dashboard_siswa.php');
                exit;
            } else {
                $error = "Role user tidak dikenali.";
            }
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}

// Setelah login diproses, baru tampilkan halaman
$title = 'Login | SDN Ciseeng 01';
include './inc/header.php';
?>

<!-- Tampilan Form Login -->
<div class="min-h-screen flex items-center justify-center bg-gradient-to-tr from-red-100 to-red-300 px-4">
  <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold mb-6 text-center text-red-600">Login Perpustakaan</h2>
    
    <?php if ($error !== ''): ?>
      <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-6">
      <div>
        <label for="username" class="block mb-2 font-semibold text-red-600">Username</label>
        <input type="text" id="username" name="username" required placeholder="Masukkan username" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" />
      </div>
      <div>
        <label for="password" class="block mb-2 font-semibold text-red-600">Password</label>
        <input type="password" id="password" name="password" required placeholder="Masukkan password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" />
      </div>
      <button type="submit" class="w-full bg-red-600 text-white font-semibold py-2 rounded-md hover:bg-red-700 transition">Login</button>
    </form>

    <p class="mt-6 text-center text-gray-600">
      Belum punya akun?
      <a href="register.php" class="text-red-600 font-semibold hover:underline">Daftar sekarang</a>
    </p>
  </div>
</div>

<?php include './inc/footer.php'; ?>
