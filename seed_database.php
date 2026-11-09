<?php
if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}

// Deteksi environment variable atau fallback ke lokal Laragon
$db_host = getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: '127.0.0.1';
$db_port = (int)(getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: 3306);
$db_user = getenv('MYSQLUSER') ?: getenv('MYSQL_USER') ?: 'root';
$db_pass_env = getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD');
$db_name = getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'spk_gangguan_tidur';

$m = null;

// Retry loop jika database masih dalam proses booting
$max_retries = 10;
$retry = 0;

while ($retry < $max_retries) {
    if ($db_pass_env !== false && $db_pass_env !== null) {
        $conn = @new mysqli($db_host, $db_user, $db_pass_env, '', $db_port);
        if (!$conn->connect_error) {
            $m = $conn;
            break;
        }
    } else {
        foreach (['anggi20', ''] as $p) {
            $conn = @new mysqli($db_host, $db_user, $p, '', $db_port);
            if (!$conn->connect_error) {
                $m = $conn;
                break 2;
            }
        }
    }
    $retry++;
    sleep(2);
}

if (!$m) {
    echo "Koneksi MySQL belum berhasil ($db_host:$db_port), melanjutkan...\n";
    return;
}

echo "Berhasil terkoneksi ke MySQL ($db_host:$db_port)!\n";

// Ensure database exists
$m->query("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
$m->select_db($db_name);

// Create tables if not exist
$m->query("CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$m->query("CREATE TABLE IF NOT EXISTS `tbl_gejala` (
  `kode_gejala` varchar(5) NOT NULL,
  `nama_gejala` varchar(150) NOT NULL,
  `keterangan` text,
  PRIMARY KEY (`kode_gejala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$m->query("CREATE TABLE IF NOT EXISTS `tbl_penyakit` (
  `kode_penyakit` varchar(5) NOT NULL,
  `nama_penyakit` varchar(100) NOT NULL,
  `saran_penanganan` text,
  PRIMARY KEY (`kode_penyakit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$m->query("CREATE TABLE IF NOT EXISTS `tbl_rule` (
  `id_rule` varchar(5) NOT NULL,
  `kode_penyakit` varchar(5) NOT NULL,
  PRIMARY KEY (`id_rule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$m->query("CREATE TABLE IF NOT EXISTS `tbl_rule_gejala` (
  `id_rule` varchar(5) NOT NULL,
  `kode_gejala` varchar(5) NOT NULL,
  PRIMARY KEY (`id_rule`,`kode_gejala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$m->query("CREATE TABLE IF NOT EXISTS `tbl_konsultasi` (
  `id_konsultasi` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `no_rm` varchar(20) DEFAULT NULL,
  `nama_pasien` varchar(100) DEFAULT NULL,
  `usia` int(11) DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `keluhan` text,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hasil_diagnosa` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_konsultasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$m->query("CREATE TABLE IF NOT EXISTS `tbl_konsultasi_gejala` (
  `id_konsultasi` int(11) NOT NULL,
  `kode_gejala` varchar(5) NOT NULL,
  PRIMARY KEY (`id_konsultasi`,`kode_gejala`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Check/Add optional columns to tbl_konsultasi if upgraded
$colCheck = $m->query("SHOW COLUMNS FROM tbl_konsultasi LIKE 'nama_pasien'");
if ($colCheck->num_rows == 0) {
    $m->query("ALTER TABLE tbl_konsultasi 
        ADD COLUMN no_rm VARCHAR(20) NULL AFTER id_user,
        ADD COLUMN nama_pasien VARCHAR(100) NULL AFTER no_rm,
        ADD COLUMN usia INT NULL AFTER nama_pasien,
        ADD COLUMN jenis_kelamin VARCHAR(20) NULL AFTER usia,
        ADD COLUMN keluhan TEXT NULL AFTER jenis_kelamin");
}

// Cek apakah tabel user sudah memiliki data (jika sudah, jangan timpa data)
$check_user = $m->query("SELECT COUNT(*) as total FROM tbl_user");
$user_count = 0;
if ($check_user) {
    $row = $check_user->fetch_assoc();
    $user_count = (int)$row['total'];
}

if ($user_count > 0) {
    echo "Database sudah terisi ($user_count user ditemukan), melewati proses seeding.\n";
    return;
}

// Disable foreign key checks for clean seeding
$m->query("SET FOREIGN_KEY_CHECKS = 0");
$m->query("TRUNCATE TABLE tbl_rule_gejala");
$m->query("TRUNCATE TABLE tbl_rule");
$m->query("TRUNCATE TABLE tbl_gejala");
$m->query("TRUNCATE TABLE tbl_penyakit");
$m->query("TRUNCATE TABLE tbl_konsultasi_gejala");
$m->query("TRUNCATE TABLE tbl_konsultasi");
$m->query("TRUNCATE TABLE tbl_user");
$m->query("SET FOREIGN_KEY_CHECKS = 1");

// 1. Seed tbl_gejala
$gejala = [
    ['G01', 'Sulit memulai tidur', 'Butuh waktu lama (>30 menit) untuk bisa tertidur'],
    ['G02', 'Sering terbangun di malam hari', 'Terbangun berkali-kali dan sulit tidur kembali'],
    ['G03', 'Merasa lelah meski sudah tidur cukup lama', 'Kualitas tidur dirasa tidak memulihkan energi'],
    ['G04', 'Mendengkur keras saat tidur', 'Suara dengkuran keras dan konsisten'],
    ['G05', 'Terengah-engah/tersedak saat tidur', 'Napas tersendat mendadak saat tidur'],
    ['G06', 'Mengalami episode berhenti napas saat tidur', 'Diamati oleh orang lain / alat monitor tidur'],
    ['G07', 'Kaki terasa tidak nyaman sebelum tidur', 'Sensasi seperti ditarik, gatal, atau kesemutan pada kaki'],
    ['G08', 'Gerakan kaki tak terkendali saat tidur', 'Kaki bergerak/menyentak secara berulang saat tidur'],
    ['G09', 'Rasa kantuk berlebihan di siang hari', 'Mengantuk berat meski tidur malam cukup'],
    ['G10', 'Tertidur tiba-tiba di situasi tidak tepat', 'Tertidur mendadak saat beraktivitas (mis. bekerja, makan)'],
    ['G11', 'Kehilangan kontrol otot mendadak (cataplexy)', 'Otot melemah tiba-tiba dipicu emosi kuat'],
    ['G12', 'Berjalan/berbicara saat tidur', 'Aktivitas motorik/verbal terjadi saat tertidur'],
    ['G13', 'Mimpi buruk berulang', 'Mimpi menakutkan yang sering terjadi dan mengganggu tidur'],
    ['G14', 'Kesulitan tidur berlangsung lebih dari 1 bulan', 'Durasi gangguan bersifat kronis']
];

$stmt = $m->prepare("INSERT INTO tbl_gejala (kode_gejala, nama_gejala, keterangan) VALUES (?, ?, ?)");
foreach ($gejala as $g) {
    $stmt->bind_param("sss", $g[0], $g[1], $g[2]);
    $stmt->execute();
}
echo "Seeded " . count($gejala) . " data gejala.\n";

// 2. Seed tbl_penyakit
$penyakit = [
    ['P01', 'Insomnia', 'Terapkan sleep hygiene, hindari kafein malam hari, dan konsultasi ke dokter jika gangguan berlanjut.'],
    ['P02', 'Sleep Apnea (Apnea Tidur)', 'Periksa ke dokter spesialis paru/THT, evaluasi berat badan, gunakan posisi tidur miring, dan pertimbangkan terapi CPAP.'],
    ['P03', 'Restless Legs Syndrome (RLS)', 'Cek kadar zat besi dan ferritin darah, kurangi konsumsi kafein/nikotin, lakukan peregangan kaki ringan sebelum tidur, dan konsultasi ke dokter spesialis saraf.'],
    ['P04', 'Narkolepsi', 'Jadwalkan tidur siang singkat yang teratur, pertahankan pola tidur disiplin, hindari mengemudi saat kantuk berat, dan konsultasi ke dokter spesialis saraf untuk terapi farmakologis.'],
    ['P05', 'Parasomnia', 'Ciptakan lingkungan tidur yang aman (jauhkan benda tajam), kelola tingkat stres, pertahankan jadwal istirahat rutin, dan hubungi profesional medis jika membahayakan keselamatan diri.']
];

$stmt = $m->prepare("INSERT INTO tbl_penyakit (kode_penyakit, nama_penyakit, saran_penanganan) VALUES (?, ?, ?)");
foreach ($penyakit as $p) {
    $stmt->bind_param("sss", $p[0], $p[1], $p[2]);
    $stmt->execute();
}
echo "Seeded " . count($penyakit) . " data penyakit.\n";

// 3. Seed tbl_rule
$rules = [
    ['R1', 'P01'],
    ['R2', 'P02'],
    ['R3', 'P03'],
    ['R4', 'P04'],
    ['R5', 'P05']
];

$stmt = $m->prepare("INSERT INTO tbl_rule (id_rule, kode_penyakit) VALUES (?, ?)");
foreach ($rules as $r) {
    $stmt->bind_param("ss", $r[0], $r[1]);
    $stmt->execute();
}
echo "Seeded " . count($rules) . " rules.\n";

// 4. Seed tbl_rule_gejala
$rule_gejala = [
    // R1: G01 AND G02 AND G14
    ['R1', 'G01'],
    ['R1', 'G02'],
    ['R1', 'G14'],
    // R2: G04 AND G05 AND G06
    ['R2', 'G04'],
    ['R2', 'G05'],
    ['R2', 'G06'],
    // R3: G07 AND G08
    ['R3', 'G07'],
    ['R3', 'G08'],
    // R4: G09 AND G10 AND G11
    ['R4', 'G09'],
    ['R4', 'G10'],
    ['R4', 'G11'],
    // R5: G12 AND G13
    ['R5', 'G12'],
    ['R5', 'G13']
];

$stmt = $m->prepare("INSERT INTO tbl_rule_gejala (id_rule, kode_gejala) VALUES (?, ?)");
foreach ($rule_gejala as $rg) {
    $stmt->bind_param("ss", $rg[0], $rg[1]);
    $stmt->execute();
}
echo "Seeded " . count($rule_gejala) . " relasi rule-gejala.\n";

// 5. Seed tbl_user
$pass_admin = password_hash('admin123', PASSWORD_DEFAULT);
$pass_user  = password_hash('user123', PASSWORD_DEFAULT);

$users = [
    ['Administrator Pakar', 'admin', $pass_admin, 'admin'],
    ['Budi Santoso', 'budi', $pass_user, 'user'],
    ['Siti Aminah', 'siti', $pass_user, 'user'],
    ['Andi Wijaya', 'andi', $pass_user, 'user'],
    ['Pasien Demo', 'user', $pass_user, 'user']
];

$stmt = $m->prepare("INSERT INTO tbl_user (nama, username, password, role) VALUES (?, ?, ?, ?)");
foreach ($users as $u) {
    $stmt->bind_param("ssss", $u[0], $u[1], $u[2], $u[3]);
    $stmt->execute();
}
echo "Seeded " . count($users) . " akun pengguna (admin & user).\n";

// 6. Seed sample consultations
$consultations = [
    [
        'id_user' => 2,
        'no_rm' => 'RM-0001',
        'nama_pasien' => 'Budi Santoso',
        'usia' => 35,
        'jenis_kelamin' => 'Laki-laki',
        'keluhan' => 'Sulit tidur dan sering terbangun malam hari',
        'tanggal' => '2026-09-01 10:15:00',
        'hasil_diagnosa' => 'P01',
        'gejala' => ['G01', 'G02', 'G14']
    ],
    [
        'id_user' => 3,
        'no_rm' => 'RM-0002',
        'nama_pasien' => 'Siti Aminah',
        'usia' => 42,
        'jenis_kelamin' => 'Perempuan',
        'keluhan' => 'Mendengkur keras dan sering tersedak saat tidur',
        'tanggal' => '2026-09-05 14:30:00',
        'hasil_diagnosa' => 'P02',
        'gejala' => ['G04', 'G05', 'G06']
    ],
    [
        'id_user' => 4,
        'no_rm' => 'RM-0003',
        'nama_pasien' => 'Andi Wijaya',
        'usia' => 28,
        'jenis_kelamin' => 'Laki-laki',
        'keluhan' => 'Kaki terasa tidak nyaman dan bergerak tak terkendali saat hendak tidur',
        'tanggal' => '2026-09-10 09:20:00',
        'hasil_diagnosa' => 'P03',
        'gejala' => ['G07', 'G08']
    ],
    [
        'id_user' => 5,
        'no_rm' => 'RM-0004',
        'nama_pasien' => 'Dewi Lestari',
        'usia' => 24,
        'jenis_kelamin' => 'Perempuan',
        'keluhan' => 'Sering mengantuk hebat di siang hari dan tiba-tiba tertidur',
        'tanggal' => '2026-09-14 11:45:00',
        'hasil_diagnosa' => 'P04',
        'gejala' => ['G09', 'G10', 'G11']
    ],
    [
        'id_user' => 5,
        'no_rm' => 'RM-0005',
        'nama_pasien' => 'Rudi Hartono',
        'usia' => 31,
        'jenis_kelamin' => 'Laki-laki',
        'keluhan' => 'Merasa lelah meski sudah tidur lama',
        'tanggal' => '2026-09-18 16:10:00',
        'hasil_diagnosa' => null,
        'gejala' => ['G03']
    ]
];

foreach ($consultations as $c) {
    $stmtC = $m->prepare("INSERT INTO tbl_konsultasi (id_user, no_rm, nama_pasien, usia, jenis_kelamin, keluhan, tanggal, hasil_diagnosa) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtC->bind_param("ississss", $c['id_user'], $c['no_rm'], $c['nama_pasien'], $c['usia'], $c['jenis_kelamin'], $c['keluhan'], $c['tanggal'], $c['hasil_diagnosa']);
    $stmtC->execute();
    $id_konsultasi = $m->insert_id;
    
    $stmtG = $m->prepare("INSERT INTO tbl_konsultasi_gejala (id_konsultasi, kode_gejala) VALUES (?, ?)");
    foreach ($c['gejala'] as $kg) {
        $stmtG->bind_param("is", $id_konsultasi, $kg);
        $stmtG->execute();
    }
}
echo "Seeded " . count($consultations) . " riwayat konsultasi awal.\n";
echo "=============================================\n";
echo "DATABASE SEEDING SELESAI DENGAN SUKSES!\n";
echo "Gunakan akun:\n";
echo "- Admin/Pakar: username 'admin', password 'admin123'\n";
echo "- Pasien Demo: username 'budi' atau 'user', password 'user123'\n";
echo "=============================================\n";
