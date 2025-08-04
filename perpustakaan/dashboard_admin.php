<?php
session_start();
require './inc/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

function getDataBuku($koneksi) {
    $sql = "SELECT b.id,b.judul,b.penulis,b.penerbit,b.stok,
                    COUNT(DISTINCT p.id) AS total_peminjaman,
                    COUNT(DISTINCT k.id) AS total_pengembalian
            FROM buku b
            LEFT JOIN peminjaman p ON b.id = p.id_buku
            LEFT JOIN pengembalian k ON p.id = k.id_peminjaman
            GROUP BY b.id";
    return mysqli_query($koneksi,$sql);
}
function getDataPeminjaman($koneksi) {
    $sql = "SELECT p.id   AS id_peminjaman,
                    u.username AS peminjam,
                    b.judul AS judul_buku,
                    p.tgl_pinjam,p.tgl_kembali,p.status
            FROM peminjaman p
            JOIN users u ON p.peminjam = u.username
            JOIN buku  b ON p.id_buku  = b.id
            ORDER BY p.tgl_pinjam DESC";
    return mysqli_query($koneksi,$sql);
}
function getPesan($koneksi,$id_user){
    $sql = "SELECT p.id,u.username AS pengirim,p.isi_pesan,p.tanggal
            FROM pesan p JOIN users u ON p.id_pengirim=u.id
            WHERE p.id_penerima={$id_user}
            ORDER BY p.tanggal DESC";
    return mysqli_query($koneksi,$sql);
}

$buku       = getDataBuku($koneksi);
$peminjaman = getDataPeminjaman($koneksi);
$pesan      = getPesan($koneksi,$_SESSION['id_user']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,html{height:100%;margin:0}
        .sidebar{height:100vh;width:220px;position:fixed;top:0;left:0;background:#343a40;color:#fff;display:flex;flex-direction:column}
        .sidebar .nav-link{color:#adb5bd;padding:15px 20px;cursor:pointer}
        .sidebar .nav-link.active,.sidebar .nav-link:hover{background:#495057;color:#fff}
        .content{margin-left:220px;padding:20px;height:100vh;overflow-y:auto;background:#f8f9fa}
        .content::-webkit-scrollbar{width:8px}
        .content::-webkit-scrollbar-thumb{background:#6c757d;border-radius:4px}
    </style>
</head>
<body>
<div class="sidebar">
    <h4 class="text-center py-3 border-bottom">Admin Panel</h4>
    <a class="nav-link active" data-tab="buku">📚 Data Buku</a>
    <a class="nav-link" data-tab="riwayat">🧾 Riwayat Peminjaman</a>
    <a class="nav-link" data-tab="pesan">📩 Pesan</a>
    <div class="mt-auto p-3 border-top"><a href="logout.php" class="btn btn-danger w-100">Logout</a></div>
</div>
<div class="content">
<?php if(isset($_GET['pesan'])):?>
    <?php if($_GET['pesan']=='hapus_sukses'):?>
        <div class="alert alert-success alert-dismissible fade show">✅ Riwayat berhasil dihapus.<button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif($_GET['pesan']=='hapus_gagal_status'):?>
        <div class="alert alert-warning alert-dismissible fade show">⚠️ Hanya dapat menghapus riwayat berstatus dikembalikan.<button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif($_GET['pesan']=='hapus_gagal'):?>
        <div class="alert alert-danger alert-dismissible fade show">❌ Gagal menghapus riwayat.<button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif($_GET['pesan']=='hapus_pesan_sukses'):?>
        <div class="alert alert-success alert-dismissible fade show">✅ Pesan berhasil dihapus.<button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif($_GET['pesan']=='hapus_pesan_gagal'):?>
        <div class="alert alert-danger alert-dismissible fade show">❌ Gagal menghapus pesan.<button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php elseif($_GET['pesan']=='id_pesan_tidak_ditemukan'):?>
        <div class="alert alert-warning alert-dismissible fade show">⚠️ ID Pesan tidak ditemukan.<button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
<?php endif; ?>
<?php if(isset($_GET['pesan']) && $_GET['pesan']=='import_sukses'): ?>
    <div class="alert alert-success alert-dismissible fade show">✅ Data buku berhasil diimpor.<button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php elseif(isset($_GET['pesan']) && $_GET['pesan']=='import_gagal'): ?>
    <div class="alert alert-danger alert-dismissible fade show">❌ Gagal mengimpor data dari Excel.<button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<h2>Selamat datang, <?=htmlspecialchars($_SESSION['username'])?></h2>

<div id="tab-buku">
    <div class="mb-3 d-flex flex-wrap gap-2">
        <a href="tambah_buku.php" class="btn btn-success">➕ Tambah Buku</a>
        <a href="export_pdf.php" class="btn btn-danger">📄 Export PDF</a>
        <a href="export_excel.php" class="btn btn-primary">📊 Export Excel</a>
        <form action="import_excel.php" method="post" enctype="multipart/form-data" class="d-inline-block">
        <input type="file" name="file_excel" accept=".xlsx,.xls" required>
        <button type="submit" class="btn btn-warning text-white">📥 Import Excel</button>
</form>
    </div>
    <div class="card shadow">
        <div class="card-header bg-primary text-white"><strong>📚 Daftar Buku & Status</strong></div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light"><tr><th>Judul</th><th>Penulis</th><th>Penerbit</th><th>Stok</th><th>Total Peminjaman</th><th>Total Pengembalian</th></tr></thead>
                <tbody>
                <?php if(mysqli_num_rows($buku)>0):while($row=mysqli_fetch_assoc($buku)):?>
                    <tr>
                        <td><?=htmlspecialchars($row['judul'])?></td><td><?=htmlspecialchars($row['penulis'])?></td><td><?=htmlspecialchars($row['penerbit'])?></td><td><?=$row['stok']?></td><td><?=$row['total_peminjaman']?></td><td><?=$row['total_pengembalian']?></td>
                    </tr>
                <?php endwhile;else:?>
                    <tr><td colspan="6" class="text-center">Belum ada data buku</td></tr>
                <?php endif; ?></tbody></table>
        </div>
    </div>
</div>

<div id="tab-riwayat" style="display:none;">
    <div class="mb-3 d-flex gap-2">
    <a href="export_pdf.php" class="btn btn-outline-danger">📄 Export PDF</a>
    <a href="export_excel.php" class="btn btn-outline-success">📊 Export Excel</a>
    <form action="import_excel.php" method="post" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
        <input type="file" name="file_excel" accept=".xlsx,.xls" required class="form-control">
        <button type="submit" class="btn btn-outline-primary">📥 Import Excel</button>
    </form>
</div>
    <div class="card shadow">
        <div class="card-header bg-secondary text-white"><strong>🧾 Riwayat Peminjaman Buku</strong></div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light"><tr><th>No</th><th>Peminjam</th><th>Judul Buku</th><th>Pinjam</th><th>Kembali</th><th>Status</th><th>Aksi</th></tr></thead>
               <tbody>
<?php if(mysqli_num_rows($peminjaman)>0): $no=1; while($pinjam=mysqli_fetch_assoc($peminjaman)):
    $status = strtolower(trim($pinjam['status']));
?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($pinjam['peminjam']) ?></td>
        <td><?= htmlspecialchars($pinjam['judul_buku']) ?></td>
        <td><?= $pinjam['tgl_pinjam'] ?></td>
        <td><?= $pinjam['tgl_kembali'] ?: '<em>Belum</em>' ?></td>
        <td><span class="badge bg-<?= $status==='dipinjam' ? 'warning' : 'success' ?>"><?= ucfirst($status) ?></span></td>
        <td>
            <a href="hapus_riwayat.php?id_peminjaman=<?= $pinjam['id_peminjaman'] ?>#riwayat" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus riwayat ini?')">🗑️ Hapus</a>
        </td>
    </tr>
<?php endwhile; else: ?>
    <tr><td colspan="7" class="text-center">Belum ada data peminjaman</td></tr>
<?php endif; ?>
</tbody>

            </table>
        </div>
    </div>
</div>
<div id="tab-pesan" style="display:none;">
    <div class="card shadow">
        <div class="card-header bg-warning"><strong>📬 Pesan dari Siswa</strong></div>
        <div class="card-body">
            <?php if(mysqli_num_rows($pesan)>0):?>
                <ul class="list-group">
                    <?php while($ps=mysqli_fetch_assoc($pesan)):?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?=htmlspecialchars($ps['pengirim'])?></strong> (<?=$ps['tanggal']?>):<br>
                                <?=htmlspecialchars($ps['isi_pesan'])?>
                            </div>
                            <a href="hapus_pesan.php?id_pesan=<?= $ps['id'] ?>#pesan" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pesan ini?');">🗑️ Hapus</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?><p class="text-muted">Belum ada pesan masuk.</p><?php endif; ?>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar links switching
    document.querySelectorAll('.sidebar .nav-link').forEach(link=>{
        link.addEventListener('click',()=>{
            document.querySelectorAll('.sidebar .nav-link').forEach(l=>l.classList.remove('active'));
            link.classList.add('active');
            const tab=link.getAttribute('data-tab');
            document.querySelectorAll('.content > div[id^="tab-"]').forEach(c=>c.style.display='none');
            document.getElementById('tab-'+tab).style.display='block';
            history.replaceState(null,null,'#'+tab); // update URL hash
        });
    });
    // Activate tab by hash on load
    window.addEventListener('DOMContentLoaded',()=>{
        const hash=location.hash.replace('#','');
        if(hash){
            const link=document.querySelector('.sidebar .nav-link[data-tab="'+hash+'"]');
            if(link) link.click();
        }
    });
</script>
</body>
</html>