<?php
require './inc/koneksi.php';
require './vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data Buku');

$sheet->fromArray(['Judul', 'Penulis', 'Penerbit', 'Stok'], null, 'A1');

$query = mysqli_query($koneksi, "SELECT * FROM buku");
$rowNum = 2;
while ($row = mysqli_fetch_assoc($query)) {
    $sheet->fromArray([$row['judul'], $row['penulis'], $row['penerbit'], $row['stok']], null, "A{$rowNum}");
    $rowNum++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="data_buku.xlsx"');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
