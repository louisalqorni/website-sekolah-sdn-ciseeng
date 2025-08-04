<?php
require_once './inc/koneksi.php';
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();
$html = '<h2>Daftar Buku Perpustakaan</h2><table border="1" cellpadding="10"><tr><th>Judul</th><th>Penulis</th><th>Penerbit</th><th>Stok</th></tr>';

$query = mysqli_query($koneksi, "SELECT * FROM buku");
while ($row = mysqli_fetch_assoc($query)) {
    $html .= "<tr>
      <td>{$row['judul']}</td>
      <td>{$row['penulis']}</td>
      <td>{$row['penerbit']}</td>
      <td>{$row['stok']}</td>
    </tr>";
}
$html .= '</table>';

$mpdf->WriteHTML($html);
$mpdf->Output("data_buku.pdf", "D");
