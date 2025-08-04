
-- --------------------------------------------------------
-- Database: `db_perpus_sekolah`
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS db_perpus_sekolah;
USE db_perpus_sekolah;

-- --------------------------------------------------------
-- Table structure for `user`
-- --------------------------------------------------------
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL
);

-- --------------------------------------------------------
-- Table structure for `buku`
-- --------------------------------------------------------
CREATE TABLE buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    penulis VARCHAR(100),
    penerbit VARCHAR(100),
    tahun_terbit YEAR,
    kategori VARCHAR(100),
    cover VARCHAR(255) DEFAULT NULL
);

-- --------------------------------------------------------
-- Table structure for `peminjaman`
-- --------------------------------------------------------
CREATE TABLE peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_buku INT NOT NULL,
    peminjam VARCHAR(100) NOT NULL,
    tgl_pinjam DATE NOT NULL DEFAULT (CURRENT_DATE),
    tgl_kembali DATE DEFAULT NULL,
    status ENUM('dipinjam','kembali') DEFAULT 'dipinjam',
    FOREIGN KEY (id_buku) REFERENCES buku(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Table structure for `berita`
-- --------------------------------------------------------
CREATE TABLE berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    gambar VARCHAR(255),
    tanggal DATE DEFAULT CURRENT_DATE
);

-- --------------------------------------------------------
-- Table structure for `galeri`
-- --------------------------------------------------------
CREATE TABLE galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100),
    gambar VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    tanggal_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- Table structure for `kontak`
-- --------------------------------------------------------
CREATE TABLE kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alamat TEXT NOT NULL,
    email VARCHAR(100),
    telepon VARCHAR(50),
    maps_embed TEXT
);

-- --------------------------------------------------------
-- Insert default admin user (password: admin123)
-- --------------------------------------------------------
INSERT INTO user (username, password, nama_lengkap)
VALUES ('admin', MD5('admin123'), 'Administrator');
