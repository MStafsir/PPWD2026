-- Jalankan file ini di HeidiSQL / phpMyAdmin sebelum menjalankan aplikasi
-- Cara: buka HeidiSQL -> klik kanan -> Load SQL file -> pilih file ini -> Execute

CREATE DATABASE IF NOT EXISTS donasi_app;
USE donasi_app;

CREATE TABLE IF NOT EXISTS donasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,   -- pakai DECIMAL, bukan FLOAT, supaya nominal uang presisi (tidak ada pembulatan aneh)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- otomatis terisi waktu saat data dimasukkan
);

-- Data contoh (opsional, boleh dihapus)
INSERT INTO donasi (nama, jumlah) VALUES
('Budi Santoso', 50000),
('Siti Aminah', 100000),
('Andi Wijaya', 25000);
