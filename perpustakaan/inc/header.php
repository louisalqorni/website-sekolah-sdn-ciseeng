<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= isset($title) ? htmlspecialchars($title) : 'SDN Ciseeng 01' ?></title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <link href="../css/style.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 font-sans text-gray-800">

<header class="bg-red-600 shadow p-4 sticky top-0 z-50">
    <div class="container mx-auto flex items-center justify-between">
        <a href="../index.html" class="text-xl font-bold text-white">SDN CISEENG 01</a>

        <nav class="hidden md:flex items-center space-x-6 text-white">

            <a href="../index.html" class="hover:text-yellow-200 transition">Beranda</a>

            <a href="../profil.html" class="hover:text-yellow-200 transition">Profil</a>

            <div class="relative group">
                <div class="flex items-center cursor-pointer text-white hover:text-yellow-200 transition">
                    <span>Informasi</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="absolute left-0 mt-2 w-40 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <a href="../guru.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-red-600">Guru&Tendik</a>
                    <a href="../kegiatan.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-red-600">Kegiatan</a>
                    <a href="../prestasi.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-red-600">Prestasi</a>
                    <a href="../blog.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-red-600">Blog</a>
                    <a href="../galeri.html" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-red-600">Galeri</a>
                </div>
            </div>

            <div class="relative group">
                <button class="flex items-center text-white hover:text-yellow-200 transition">
                    Perpustakaan
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition">
                    <?php if (isset($_SESSION['role'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="dashboard_admin.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-red-600">Dashboard Admin</a>
                        <?php else: ?>
                            <a href="dashboard_siswa.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-red-600">Dashboard Siswa</a>
                        <?php endif; ?>
                        <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-red-600">Login</a>
                        <a href="register.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 hover:text-red-600">Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <button id="btn-menu" aria-label="Toggle menu" class="md:hidden focus:outline-none">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="3" y1="6" x2="21" y2="6" stroke-linecap="round"></line>
                <line x1="3" y1="12" x2="21" y2="12" stroke-linecap="round"></line>
                <line x1="3" y1="18" x2="21" y2="18" stroke-linecap="round"></line>
            </svg>
        </button>
    </div>
</header>

<nav id="mobile-menu" class="hidden md:hidden bg-red-600 shadow-md px-4 pb-4 space-y-1">
    <a href="../index.html" class="block py-2 border-b border-red-500 text-white hover:text-yellow-200">Beranda</a>
    <a href="../profil.html" class="block py-2 border-b border-red-500 text-white hover:text-yellow-200">Profil</a>

    <div>
        <button id="toggle-informasi" class="w-full text-left py-2 font-semibold text-white hover:text-yellow-200 focus:outline-none">
            Informasi
        </button>
        <div id="submenu-informasi" class="pl-4 space-y-1 hidden">
            <a href="../guru.html" class="block py-1 text-white hover:text-yellow-200">Guru&Tendik</a>
            <a href="../kegiatan.html" class="block py-1 text-white hover:text-yellow-200">Kegiatan</a>
            <a href="../prestasi.html" class="block py-1 text-white hover:text-yellow-200">Prestasi</a>
            <a href="../blog.html" class="block py-1 text-white hover:text-yellow-200">Blog</a>
            <a href="../galeri.html" class="block py-1 text-white hover:text-yellow-200">Galeri</a>
        </div>
    </div>

    <a href="../perpustakaan.html" class="block py-2 border-b border-red-500 text-white hover:text-yellow-200">Perpustakaan</a>
</nav>

<script>
    AOS.init();

    // Toggle mobile menu
    const btnMenu = document.getElementById('btn-menu');
    const mobileMenu = document.getElementById('mobile-menu');
    btnMenu.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Toggle submenu "Informasi" di mobile
    const btnInformasi = document.getElementById('toggle-informasi');
    const submenuInformasi = document.getElementById('submenu-informasi');
    btnInformasi.addEventListener('click', () => {
        submenuInformasi.classList.toggle('hidden');
    });
</script>

</body>
</html>