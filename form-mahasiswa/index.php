<?php
$dataMahasiswa = [];
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $nim = trim($_POST['nim'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);

    if (
        $nama === '' || mb_strlen($nama) > 100 ||
        $nim === '' || mb_strlen($nim) > 30 ||
        $jurusan === '' ||
        $semester === false || $semester === null ||
        $semester < 1 || $semester > 14
    ) {
        $pesan = 'Semua kolom wajib diisi dengan benar.';
    } else {
        $jurusanDiizinkan = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Manajemen Informatika',
            'Teknik Komputer'
        ];

        if (!in_array($jurusan, $jurusanDiizinkan, true)) {
            $pesan = 'Jurusan tidak valid.';
        } else {
            $dataMahasiswa[] = [
                'nama' => $nama,
                'nim' => $nim,
                'jurusan' => $jurusan,
                'semester' => $semester
            ];
        }
    }
}

function e($nilai): string
{
    return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Data Mahasiswa</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 30px 16px;
            color: #1e293b;
        }
        .container { max-width: 900px; margin: auto; }
        .card {
            background: white;
            padding: 24px;
            margin-bottom: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 15px #0000000d;
        }
        h1, h2 { margin-top: 0; }
        h1 { color: #1d4ed8; }
        label { display: block; margin: 14px 0 6px; font-weight: bold; }
        input, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 15px;
        }
        button {
            margin-top: 20px;
            padding: 12px 20px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover { background: #1d4ed8; }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
        }
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 550px; }
        th, td { border: 1px solid #e2e8f0; padding: 12px; text-align: left; }
        th { background: #2563eb; color: white; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        .empty { text-align: center; color: #64748b; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>Form Data Mahasiswa</h1>
        <p>Masukkan data mahasiswa melalui formulir berikut.</p>

        <?php if ($pesan !== ''): ?>
            <p class="error"><?= e($pesan) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" maxlength="100" required>

            <label for="nim">NIM</label>
            <input type="text" id="nim" name="nim" maxlength="30" required>

            <label for="jurusan">Jurusan</label>
            <select id="jurusan" name="jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Sistem Informasi">Sistem Informasi</option>
                <option value="Manajemen Informatika">Manajemen Informatika</option>
                <option value="Teknik Komputer">Teknik Komputer</option>
            </select>

            <label for="semester">Semester</label>
            <select id="semester" name="semester" required>
                <option value="">-- Pilih Semester --</option>
                <?php for ($i = 1; $i <= 14; $i++): ?>
                    <option value="<?= $i ?>">Semester <?= $i ?></option>
                <?php endfor; ?>
            </select>

            <button type="submit">Tampilkan Data</button>
        </form>
    </div>

    <div class="card">
        <h2>Tabel Data Mahasiswa</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Jurusan</th>
                        <th>Semester</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($dataMahasiswa) > 0): ?>
                    <?php foreach ($dataMahasiswa as $index => $mhs): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= e($mhs['nama']) ?></td>
                            <td><?= e($mhs['nim']) ?></td>
                            <td><?= e($mhs['jurusan']) ?></td>
                            <td><?= e($mhs['semester']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty">Belum ada data. Silakan isi formulir.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
