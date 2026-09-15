<?php
/**
 * index.php
 * -----------------------------------------
 * Halaman ini bertugas MENAMPILKAN data (READ).
 * Method yang dipakai di sini: GET (default setiap kali buka URL di browser).
 *
 * GET dipakai untuk: mengambil/menampilkan data, tanpa mengubah apapun di database.
 * Ciri khasnya: data dikirim lewat URL (contoh: index.php?pesan=sukses),
 * makanya GET tidak cocok untuk data sensitif atau data besar.
 */

require 'config.php';

// Mengambil semua data donasi dari database, diurutkan dari yang terbaru
$stmt = $pdo->query("SELECT * FROM donasi ORDER BY created_at DESC");
$daftarDonasi = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menghitung total donasi terkumpul (untuk ditampilkan di atas)
$stmtTotal = $pdo->query("SELECT SUM(jumlah) AS total FROM donasi");
$total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

/**
 * Contoh pemakaian GET: menangkap parameter dari URL.
 * Setelah proses_tambah.php atau hapus.php selesai, mereka akan redirect
 * ke index.php?pesan=sukses, lalu kode di bawah ini membaca parameter itu
 * untuk menampilkan notifikasi.
 *
 * $_GET adalah "superglobal" bawaan PHP yang otomatis berisi semua data
 * yang dikirim lewat query string URL (bagian setelah tanda '?').
 */
$pesan = $_GET['pesan'] ?? null;

/**
 * Fungsi bantu untuk memformat angka jadi format Rupiah.
 * number_format() adalah fungsi bawaan PHP untuk memformat angka.
 */
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Donasi Sederhana</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <div class="container">
        <h1>💝 Donasi Sederhana</h1>

        <div class="total-card">
            <p>Total Donasi Terkumpul</p>
            <h2><?= formatRupiah($total) ?></h2>
            <span><?= count($daftarDonasi) ?> donatur</span>
        </div>

        <?php if ($pesan === 'sukses_tambah'): ?>
            <div class="alert alert-sukses">✅ Donasi berhasil ditambahkan. Terima kasih!</div>
        <?php elseif ($pesan === 'sukses_hapus'): ?>
            <div class="alert alert-sukses">🗑️ Data donasi berhasil dihapus.</div>
        <?php elseif ($pesan === 'gagal'): ?>
            <div class="alert alert-gagal">❌ Terjadi kesalahan. Coba lagi.</div>
        <?php endif; ?>

        <div class="form-card">
            <h3>Form Donasi</h3>

            <!--
                method="POST" dipakai di sini karena kita MENGIRIM data baru
                yang akan MENGUBAH isi database (insert data baru).
                POST menyembunyikan data dari URL (lebih cocok untuk input form),
                dan tidak ada batas ukuran data seperti GET.

                action="proses_tambah.php" artinya form ini akan diproses
                oleh file proses_tambah.php, bukan diproses di halaman ini.
            -->
            <form action="proses_tambah.php" method="POST" id="formDonasi">
                <div class="form-group">
                    <label for="nama">Nama Donatur</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama" required>
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah Donasi (Rp)</label>
                    <input type="number" id="jumlah" name="jumlah" placeholder="Contoh: 50000" min="1000" required>
                    <!-- Elemen ini akan diisi otomatis oleh JS untuk preview format Rupiah -->
                    <small id="previewRupiah" class="preview"></small>
                </div>

                <button type="submit" class="btn-submit">Kirim Donasi</button>
            </form>
        </div>

        <div class="list-card">
            <h3>Daftar Donatur</h3>

            <?php if (empty($daftarDonasi)): ?>
                <p class="kosong">Belum ada donasi masuk.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daftarDonasi as $donasi): ?>
                            <tr>
                                <!--
                                    htmlspecialchars() WAJIB dipakai setiap menampilkan
                                    data dari database/user ke HTML. Fungsinya mengubah
                                    karakter berbahaya (misal <script>) jadi teks biasa,
                                    supaya mencegah serangan XSS (Cross-Site Scripting).
                                -->
                                <td><?= htmlspecialchars($donasi['nama']) ?></td>
                                <td><?= formatRupiah($donasi['jumlah']) ?></td>
                                <td><?= date('d M Y, H:i', strtotime($donasi['created_at'])) ?></td>
                                <td>
                                    <!--
                                        Link hapus ini pakai GET (lewat URL: hapus.php?id=3)
                                        karena cuma mengirim satu nilai kecil (id) dan
                                        bukan data form yang kompleks. Konfirmasi dulu
                                        pakai JavaScript (onclick) sebelum benar-benar hapus.
                                    -->
                                    <a href="hapus.php?id=<?= $donasi['id'] ?>"
                                       class="btn-hapus"
                                       onclick="return konfirmasiHapus('<?= htmlspecialchars($donasi['nama']) ?>')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script src="/js/script.js"></script>
</body>
</html>