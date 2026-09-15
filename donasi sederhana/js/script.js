/**
 * script.js - JavaScript dasar (client-side, jalan di browser)
 * -----------------------------------------
 * File ini TIDAK berhubungan dengan PHP/database sama sekali.
 * PHP jalan di SERVER (sebelum halaman dikirim ke browser),
 * sedangkan file ini jalan di BROWSER pengguna, SETELAH halaman termuat.
 */

// Mengambil elemen input jumlah dan elemen kecil untuk preview
const inputJumlah = document.getElementById('jumlah');
const previewRupiah = document.getElementById('previewRupiah');

/**
 * Fungsi untuk memformat angka menjadi format Rupiah, contoh: 50000 -> "Rp 50.000"
 * Ini VERSI JAVASCRIPT dari fungsi formatRupiah() yang ada di index.php.
 * Keduanya sengaja dibuat terpisah karena PHP jalan di server (format untuk
 * data yang SUDAH tersimpan), sedangkan JS ini jalan di browser (format
 * angka SAAT user sedang mengetik, sebelum data dikirim).
 */
function formatRupiahJS(angka) {
    return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

/**
 * 'input' event: kode di dalamnya akan dijalankan browser SETIAP KALI
 * user mengetik/mengubah isi field jumlah (real-time, tanpa perlu submit).
 */
if (inputJumlah) {
    inputJumlah.addEventListener('input', function () {
        const nilai = this.value;

        if (nilai && nilai > 0) {
            previewRupiah.textContent = formatRupiahJS(nilai);
        } else {
            previewRupiah.textContent = '';
        }
    });
}

/**
 * Fungsi konfirmasi sebelum menghapus data.
 * Dipanggil dari atribut onclick="return konfirmasiHapus(...)" di index.php.
 *
 * confirm() adalah fungsi bawaan browser yang menampilkan dialog Ya/Batal.
 * Fungsi ini HARUS return true/false:
 * - true  -> browser lanjut mengikuti link <a href="hapus.php?id=...">
 * - false -> browser MEMBATALKAN aksi klik link tersebut
 */
function konfirmasiHapus(nama) {
    return confirm('Yakin ingin menghapus donasi dari "' + nama + '"?');
}

/**
 * Validasi tambahan di sisi client sebelum form dikirim (submit).
 * Ini LAPISAN PERTAMA validasi (untuk UX, respons cepat tanpa reload halaman).
 * Validasi di server (proses_tambah.php) tetap WAJIB ada sebagai lapisan
 * kedua/utama, karena validasi JS ini bisa dilewati (browser aneh-aneh,
 * JS dimatikan, dsb).
 */
const form = document.getElementById('formDonasi');

if (form) {
    form.addEventListener('submit', function (event) {
        const nama = document.getElementById('nama').value.trim();
        const jumlah = document.getElementById('jumlah').value;

        if (nama === '' || jumlah === '' || jumlah < 1000) {
            event.preventDefault(); // Membatalkan submit form (tidak jadi dikirim ke server)
            alert('Mohon isi nama dan jumlah donasi minimal Rp 1.000');
        }
    });
}