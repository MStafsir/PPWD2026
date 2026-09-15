<?php
/**
 * hapus.php
 * -----------------------------------------
 * File ini bertugas MENGHAPUS data (DELETE), dipicu dari link di index.php
 * yang formatnya: hapus.php?id=3
 *
 * Kenapa dipilih GET, bukan POST?
 * Karena aksi ini cuma mengirim 1 nilai kecil (id) lewat link <a href="">,
 * bukan lewat form. Secara teori "best practice" REST, aksi yang MENGUBAH
 * data idealnya pakai POST/DELETE, tapi untuk materi dasar/pemula,
 * pola GET lewat link seperti ini masih umum dipakai karena lebih sederhana.
 */

require 'config.php';

/** @var PDO $pdo */

/**
 * Mengambil parameter "id" dari URL lewat $_GET.
 * (int) di depannya adalah "type casting" -- memaksa nilai jadi angka integer.
 * Ini penting sebagai keamanan: kalau ada yang isi id dengan teks aneh
 * (misal untuk mencoba SQL Injection), otomatis akan jadi 0, bukan dieksekusi.
 */
$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    // Tetap pakai prepared statement walau datanya cuma angka -- konsisten aman
    $stmt = $pdo->prepare("DELETE FROM donasi WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php?pesan=sukses_hapus");
    exit;
} else {
    header("Location: index.php?pesan=gagal");
    exit;
}