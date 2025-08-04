-- Tambahan skema untuk fitur denda & pesan
USE db_perpus_sekolah;

-- ROLE
ALTER TABLE user ADD COLUMN role ENUM('admin','siswa') DEFAULT 'siswa' AFTER nama_lengkap;

-- DENDA
CREATE TABLE IF NOT EXISTS denda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_peminjaman INT NOT NULL,
  jumlah DECIMAL(10,2) NOT NULL,
  keterangan VARCHAR(255),
  status ENUM('belum dibayar','lunas') DEFAULT 'belum dibayar',
  FOREIGN KEY (id_peminjaman) REFERENCES peminjaman(id) ON DELETE CASCADE
);

-- PESAN
CREATE TABLE IF NOT EXISTS pesan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pengirim_id INT NOT NULL,
  penerima_id INT NOT NULL,
  subjek VARCHAR(255),
  isi TEXT,
  waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (pengirim_id) REFERENCES user(id) ON DELETE CASCADE,
  FOREIGN KEY (penerima_id) REFERENCES user(id) ON DELETE CASCADE
);
