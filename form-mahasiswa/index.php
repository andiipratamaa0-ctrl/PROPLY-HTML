<?php
// Form data mahasiswa menggunakan satu file PHP tanpa CSS atau JavaScript.
session_start();

if (!isset($_SESSION['dataMahasiswa'])) {
    $_SESSION['dataMahasiswa'] = [];
}

$pesan = '';

function e($nilai): string
{
    return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $nim = trim($_POST['nim'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);

    $jurusanDiizinkan = [
        'Teknik Informatika',
        'Sistem Informasi',
        'Manajemen Informatika',
        'Teknik Komputer'
    ];

    if (
        $nama === '' || mb_strlen($nama) > 100 ||
        $nim === '' || mb_strlen($nim) > 30 ||
        !in_array($jurusan, $jurusanDiizinkan, true) ||
        $semester === false || $semester === null ||
        $semester < 1 || $semester > 14
    ) {
        $pesan = 'Semua kolom wajib diisi dengan benar.';
    } else {
        $_SESSION['dataMahasiswa'][] = [
            'nama' => $nama,
            'nim' => $nim,
            'jurusan' => $jurusan,
            'semester' => $semester
        ];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

$dataMahasiswa = $_SESSION['dataMahasiswa'];

echo '<!DOCTYPE html>';
echo '<html lang="id"><head><meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Form Data Mahasiswa</title></head><body>';
echo '<h1>Form Data Mahasiswa</h1>';

if ($pesan !== '') {
    echo '<p>' . e($pesan) . '</p>';
}

echo '<form method="post">';
echo '<p><label>Nama Lengkap: <input type="text" name="nama" maxlength="100" required></label></p>';
echo '<p><label>NIM: <input type="text" name="nim" maxlength="30" required></label></p>';
echo '<p><label>Jurusan: <select name="jurusan" required>';
echo '<option value="">-- Pilih Jurusan --</option>';
foreach (['Teknik Informatika', 'Sistem Informasi', 'Manajemen Informatika', 'Teknik Komputer'] as $pilihan) {
    echo '<option value="' . e($pilihan) . '">' . e($pilihan) . '</option>';
}
echo '</select></label></p>';
echo '<p><label>Semester: <select name="semester" required>';
echo '<option value="">-- Pilih Semester --</option>';
for ($i = 1; $i <= 14; $i++) {
    echo '<option value="' . $i . '">' . $i . '</option>';
}
echo '</select></label></p>';
echo '<button type="submit">Simpan Data</button>';
echo '</form>';

echo '<h2>Tabel Data Mahasiswa</h2>';
echo '<table border="1" cellpadding="8" cellspacing="0">';
echo '<thead><tr><th>No.</th><th>Nama</th><th>NIM</th><th>Jurusan</th><th>Semester</th></tr></thead><tbody>';

if (count($dataMahasiswa) === 0) {
    echo '<tr><td colspan="5">Belum ada data mahasiswa.</td></tr>';
} else {
    foreach ($dataMahasiswa as $index => $mhs) {
        echo '<tr>';
        echo '<td>' . ($index + 1) . '</td>';
        echo '<td>' . e($mhs['nama']) . '</td>';
        echo '<td>' . e($mhs['nim']) . '</td>';
        echo '<td>' . e($mhs['jurusan']) . '</td>';
        echo '<td>' . e($mhs['semester']) . '</td>';
        echo '</tr>';
    }
}

echo '</tbody></table>';
echo '</body></html>';
