<?php
/**
 * proses_tambah.php
 * -----------------------------------------
 * File ini bertugas MEMPROSES data yang dikirim dari form (CREATE).
 * Tidak menampilkan HTML apapun -- hanya logika, lalu redirect kembali ke index.php.
 */

require 'config.php';

/**
 * $_SERVER['REQUEST_METHOD'] adalah cara PHP mengecek method HTTP apa
 * yang dipakai untuk mengakses file ini (GET, POST, dll).
 *
 * Pengecekan ini PENTING sebagai keamanan dasar: mencegah file ini
 * dieksekusi kalau seseorang cuma membuka proses_tambah.php langsung
 * lewat browser (yang otomatis pakai method GET), bukan lewat submit form.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /**
     * $_POST adalah superglobal PHP yang berisi semua data yang dikirim
     * form dengan method="POST". Key di dalam $_POST harus sama dengan
     * atribut "name" pada input di file index.php (name="nama", name="jumlah").
     *
     * trim() menghapus spasi berlebih di awal/akhir teks.
     */
    $nama   = trim($_POST['nama'] ?? '');
    $jumlah = trim($_POST['jumlah'] ?? '');

    /**
     * Validasi sederhana di sisi server (server-side validation).
     * Ini WAJIB ada walaupun sudah ada validasi di HTML (required, min="1000"),
     * karena validasi HTML/JS bisa saja dilewati (misal user mematikan JS,
     * atau mengirim request langsung tanpa lewat form). Validasi di server
     * adalah pertahanan terakhir yang tidak bisa dilewati.
     */
    if ($nama === '' || $jumlah === '' || !is_numeric($jumlah) || $jumlah < 1000) {
        // Kalau data tidak valid, redirect balik dengan pesan gagal
        header("Location: index.php?pesan=gagal");
        exit; // exit WAJIB setelah header(Location:...) supaya script berhenti total
    }

    /**
     * Prepared Statement (pakai tanda "?" sebagai placeholder).
     * Ini cara AMAN untuk insert data ke database, mencegah SQL Injection.
     *
     * JANGAN PERNAH menulis query seperti ini (RAWAN SQL INJECTION):
     *   $pdo->query("INSERT INTO donasi (nama, jumlah) VALUES ('$nama', '$jumlah')");
     *
     * Dengan prepared statement, nilai $nama dan $jumlah "dititipkan" secara
     * terpisah dari struktur query, sehingga tidak bisa disisipi kode SQL jahat.
     */
    /** @var PDO $pdo */
    $stmt = $pdo->prepare("INSERT INTO donasi (nama, jumlah) VALUES (?, ?)");
    $stmt->execute([$nama, $jumlah]);

    // Redirect ke index.php dengan pesan sukses (pola PRG: Post-Redirect-Get,
    // supaya kalau user refresh halaman, form tidak ter-submit dobel)
    header("Location: index.php?pesan=sukses_tambah");
    exit;

} else {
    // Kalau file ini diakses langsung lewat GET (bukan submit form), tolak akses
    header("Location: index.php");
    exit;
}