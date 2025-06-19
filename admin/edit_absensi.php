<?php
session_start();
include '../koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: kelola_absensi.php");
    exit();
}

$id = intval($_GET['id']);

// Ambil data dari DB
$result = $conn->query("SELECT * FROM absensi WHERE id = $id");
$data = $result->fetch_assoc();

if (!$data) {
    echo "Data tidak ditemukan!";
    exit();
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];

    $stmt = $conn->prepare("UPDATE absensi SET nama = ?, tanggal = ?, keterangan = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nama, $tanggal, $keterangan, $id);
    $stmt->execute();

    header("Location: kelola_absensi.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Absensi</title>
    <style>
        body {
            font-family: sans-serif;
            background: url('../img/mount4.png') no-repeat center center fixed;
            background-size: cover;
            padding: 50px;
            color: #fff;
        }

        .form-container {
            background: rgba(255,255,255,0.9);
            color: #000;
            padding: 30px;
            max-width: 500px;
            margin: auto;
            border-radius: 10px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background: #333;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            color: #333;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Data Absensi</h2>
        <form method="post">
            <label>Nama:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

            <label>Tanggal:</label>
            <input type="date" name="tanggal" value="<?= htmlspecialchars($data['tanggal']) ?>" required>

            <label>Keterangan:</label>
            <textarea name="keterangan" rows="4" required><?= htmlspecialchars($data['keterangan']) ?></textarea>

            <button type="submit">Simpan Perubahan</button>
        </form>
        <a href="kelola_absensi.php">← Kembali</a>
    </div>
</body>
</html>
