<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="header">
        <img src="foto1.jpeg" alt="Foto Profil">
        <h1>MUHAMAD SHOLCHIN TAFSIR SRILINTANG</h1>
        <p>H1101251016 KELAS PRAKTIKUM PEMROGRAMAN WEB DASAR</p>
        <p>Program Studi: SISTEM INFORMASI B</p>
    </header>

    <nav class="nav">
        <a href="#tentang">Tentang</a>
        <a href="#hobi">Hobi</a>
        <a href="#jadwal">Jadwal</a>
        <a href="#kontak">Kontak</a>
    </nav>

    <main class="konten">
        <section id="tentang">
            <h2>Tentang Saya</h2>
            <p>Halo Saya MUHAMAD SHOLCHIN TAFSIR SRILINTANG, mahasiswa Program Studi SISTEM INFORMASI B dengan NIM
                H1101251016. Saya sedang mengikuti KELAS PRAKTIKUM PEMROGRAMAN WEB DASAR.</p>
            <p>Cita-cita saya menjadi seorang web developer yang hengker.</p>
        </section>

        <section id="hobi">
            <h2>Daftar Hobi</h2>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama Hobi</th>
                    <th>Keterangan</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Melihat MEME</td>
                    <td>Mellihat MEME terbaru dan fresh serta absrud dan juga lucu gak bisa ditebak</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>VIBE Coding</td>
                    <td>Memperbudak AI untuk membuat projek saya hingga kelar</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Music</td>
                    <td>Mendengarkan musik tanpa iklan dari website hasil VIBE Coding saya yg sangat membantu</td>
                </tr>
            </table>
        </section>

        <section id="jadwal">
            <h2>Jadwal Pelajaran Favorit</h2>
            <table>
                <tr>
                    <th>Hari</th>
                    <th>Mata Pelajaran</th>
                    <th>Jam</th>
                </tr>
                <tr>
                    <td>Senin</td>
                    <td>Pemrograman Web</td>
                    <td>07.00 – 09.30</td>
                </tr>
                <tr>
                    <td>Rabu</td>
                    <td>Basis Data</td>
                    <td>08.00 – 10.30</td>
                </tr>
                <tr>
                    <td>Jumat</td>
                    <td>Desain Grafis</td>
                    <td>07.45 – 09.15</td>
                </tr>
            </table>
        </section>

        <section id="kontak">
            <h2>Formulir Kontak</h2>
            <form>
                <label for="nama">Nama</label>
                <input type="text" id="nama" placeholder="Tulis nama Anda">

                <label for="email">Email</label>
                <input type="email" id="email" placeholder="nama@email.com">

                <label for="pesan">Pesan</label>
                <textarea id="pesan" rows="4" placeholder="Tulis pesan..."></textarea>

                <button type="submit">Kirim Pesan</button>
            </form>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2026 MUHAMAD SHOLCHIN TAFSIR SRILINTANG. Dibuat dengan HTML &amp; CSS.</p>
    </footer>

</body>

</html>