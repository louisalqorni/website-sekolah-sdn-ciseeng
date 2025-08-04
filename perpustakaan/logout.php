<?php
session_start();
session_destroy();

// Arahkan ke halaman beranda setelah logout
header("Location: ../index.html");
exit;
?>