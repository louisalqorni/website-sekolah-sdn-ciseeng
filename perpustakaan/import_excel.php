<?php
require './inc/koneksi.php';
require './vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    if ($_FILES['file_excel']['error'] === UPLOAD_ERR_OK) {
        $filename = $_FILES['file_excel']['tmp_name'];
        $spreadsheet = IOFactory::load($filename);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        for ($i = 1; $i < count($sheetData); $i++) {
            [$judul, $penulis, $penerbit, $stok] = $sheetData[$i];
            $judul = mysqli_real_escape_string($koneksi, $judul);
            $penulis = mysqli_real_escape_string($koneksi, $penulis);
            $penerbit = mysqli_real_escape_string($koneksi, $penerbit);
            $stok = (int)$stok;

            mysqli_query($koneksi, "INSERT INTO buku (judul, penulis, penerbit, stok) VALUES ('$judul','$penulis','$penerbit',$stok)");
        }

        header('Location: dashboard_admin.php?pesan=import_sukses#buku');
        exit;
    } else {
        throw new Exception('Upload file gagal dengan kode error: ' . $_FILES['file_excel']['error']);
    }
} catch (Exception $e) {
    echo 'Error saat import data: ', $e->getMessage();
}
