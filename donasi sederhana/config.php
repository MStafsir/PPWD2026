<?php
/**
 * config.php
 * -----------------------------------------
 * File ini HANYA berisi koneksi ke database.
 * Di-require (dipanggil) oleh file lain yang butuh akses database,
 * supaya kita tidak perlu menulis ulang kode koneksi di setiap file.
 */

// Kredensial database fleksibel (otomatis menyesuaikan Windows Laragon/XAMPP & MacBook MAMP/Homebrew)
$dbname = 'donasi_app';

// Deteksi apakah sistem operasi adalah macOS (Darwin) atau Windows/lainnya
$isMac = (PHP_OS_FAMILY === 'Darwin');

// Daftar calon konfigurasi kredensial:
// - Windows Laragon/XAMPP: user 'root', pass '' (kosong), port 3306
// - MacBook MAMP: user 'root', pass 'root', port 8889 atau 3306
// - MacBook Homebrew: user 'root', pass '', port 3306
$candidates = [];

if ($isMac) {
    $candidates = [
        ['host' => '127.0.0.1', 'port' => 8889, 'user' => 'root', 'pass' => 'root'],
        ['host' => 'localhost', 'port' => 8889, 'user' => 'root', 'pass' => 'root'],
        ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'pass' => 'root'],
        ['host' => 'localhost', 'port' => 3306, 'user' => 'root', 'pass' => 'root'],
        ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'pass' => ''],
        ['host' => 'localhost', 'port' => 3306, 'user' => 'root', 'pass' => ''],
        ['unix_socket' => '/Applications/MAMP/tmp/mysql/mysql.sock', 'user' => 'root', 'pass' => 'root'],
    ];
} else {
    $candidates = [
        ['host' => 'localhost', 'port' => 3306, 'user' => 'root', 'pass' => ''],
        ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'pass' => ''],
        ['host' => 'localhost', 'port' => 3306, 'user' => 'root', 'pass' => 'root'],
        ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'pass' => 'root'],
        ['host' => '127.0.0.1', 'port' => 8889, 'user' => 'root', 'pass' => 'root'],
    ];
}

$pdo = null;
$activeConfig = null;
$lastError = null;

foreach ($candidates as $c) {
    try {
        $dsnParts = [];
        if (!empty($c['unix_socket']) && file_exists($c['unix_socket'])) {
            $dsnParts[] = "unix_socket={$c['unix_socket']}";
        } else {
            if (empty($c['host'])) continue;
            $dsnParts[] = "host={$c['host']}";
            if (!empty($c['port'])) {
                $dsnParts[] = "port={$c['port']}";
            }
        }
        $dsnParts[] = "dbname=$dbname";
        $dsnParts[] = "charset=utf8mb4";
        $dsn = "mysql:" . implode(';', $dsnParts);

        $pdo = new PDO($dsn, $c['user'], $c['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 2,
        ]);

        $activeConfig = $c;
        break;
    } catch (PDOException $e) {
        $lastError = $e->getMessage();

        // Jika database belum ada di MySQL (error 1049: Unknown database),
        // otomatis buat database-nya agar siap pakai di device baru tanpa repot import manual
        if ($e->getCode() == 1049 || strpos($e->getMessage(), 'Unknown database') !== false) {
            try {
                $serverDsn = "mysql:host={$c['host']}" . (!empty($c['port']) ? ";port={$c['port']}" : "") . ";charset=utf8mb4";
                $serverPdo = new PDO($serverDsn, $c['user'], $c['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                $serverPdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

                $pdo = new PDO($dsn, $c['user'], $c['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                $activeConfig = $c;
                break;
            } catch (Exception $ex) {
                $lastError = $ex->getMessage();
            }
        }
    }
}

if (!$pdo) {
    die("Koneksi database gagal: " . htmlspecialchars($lastError) . "<br><br><small>Pastikan MySQL server aktif di Laragon (Windows) atau MAMP (MacBook).</small>");
}

// Inisialisasi tabel otomatis jika tabel 'donasi' belum ada
try {
    $pdo->query("SELECT 1 FROM donasi LIMIT 1");
} catch (Exception $e) {
    $sqlFile = __DIR__ . '/database.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        $pdo->exec($sql);
    }
}

// Set variabel untuk kompatibilitas
$host = $activeConfig['host'] ?? 'localhost';
$user = $activeConfig['user'] ?? 'root';
$pass = $activeConfig['pass'] ?? '';
$port = $activeConfig['port'] ?? 3306;