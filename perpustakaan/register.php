<?php
session_start();
require './inc/koneksi.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $username = mysqli_real_escape_string($koneksi, $_POST['username']);
  $password = $_POST['password'];
  $password2 = $_POST['password2'];

  // Validasi password sama atau tidak
  if ($password !== $password2) {
    $error = "Password tidak cocok.";
  } else {
    // Cek username sudah ada atau belum
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
      $error = "Username sudah digunakan.";
    } else {
      // Hash password
      $hash = md5($password);
      $role = 'siswa';

      // Insert data user baru
      $sql = "INSERT INTO users (nama, username, password, role) VALUES ('$nama', '$username', '$hash', '$role')";
      if (mysqli_query($koneksi, $sql)) {
        // Redirect ke halaman login setelah berhasil daftar
        header("Location: login.php");
        exit;
      } else {
        $error = "Gagal mendaftar, coba lagi.";
      }
    }
  }
}
?>

<?php $title = "Register"; ?>
<?php include './inc/header.php'; ?>

<!-- Bungkus seluruh konten dengan flex agar footer bisa dorong ke bawah -->
<div class="min-h-screen flex items-center justify-center bg-gradient-to-tr from-red-100 to-red-300 px-4">
  <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold mb-6 text-center text-red-700">Daftar Akun Siswa</h2>

    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-4">
      <input type="text" name="nama" placeholder="Nama Lengkap" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-600" />
      <input type="text" name="username" placeholder="Username" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-600" />
      <input type="password" name="password" placeholder="Password" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-600" />
      <input type="password" name="password2" placeholder="Ulangi Password" required class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-600" />
      <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">Daftar</button>
    </form>

    <p class="mt-6 text-center text-gray-600">
      Sudah punya akun?
      <a href="login.php" class="text-red-600 font-semibold hover:underline">Login di sini</a>
    </p>
  </div>
</div>

<?php include './inc/footer.php'; ?>

