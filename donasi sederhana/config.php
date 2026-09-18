<?php
/**
 * config.php
 * -----------------------------------------
 * File ini HANYA berisi koneksi ke database.
 * Di-require (dipanggil) oleh file lain yang butuh akses database,
 * supaya kita tidak perlu menulis ulang kode koneksi di setiap file.
 */

// Kredensial database Laragon (default: user root, password kosong)
$host   = 'localhost';
$dbname = 'donasi_app';
$user   = 'root';
$pass   = '';

try {
    // PDO = PHP Data Object, cara modern & aman untuk konek ke database
    // charset=utf8mb4 supaya karakter khusus (emoji, dll) tersimpan dengan benar
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);

    // ERRMODE_EXCEPTION: kalau ada query yang error, PHP akan langsung
    // melempar Exception (bisa ditangkap dengan try-catch), bukan diam-diam gagal.
    // Ini memudahkan debugging saat belajar.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Kalau koneksi gagal (misal Laragon belum di-start, atau nama database salah)
    // die() akan menghentikan eksekusi script dan menampilkan pesan error.
    die("Koneksi database gagal: " . $e->getMessage());
}