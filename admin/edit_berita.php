<?php
require_once('../koneksi.php');
session_start();

// Proteksi halaman admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_member.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: kelola_berita.php?status=error&message=ID berita tidak ditemukan');
    exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    try {
        $query = "UPDATE berita SET judul = ?, deskripsi = ?, tanggal = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $judul, $deskripsi, $tanggal, $id);
        
        if ($stmt->execute()) {
            header('Location: kelola_berita.php?status=success&message=Berita berhasil diupdate');
            exit;
        } else {
            throw new Exception("Gagal mengupdate berita");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Ambil data berita
$query = "SELECT * FROM berita WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$berita = $result->fetch_assoc();

if (!$berita) {
    header('Location: kelola_berita.php?status=error&message=Berita tidak ditemukan');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Berita</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('../img/mount4.png') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            max-width: 800px;
            margin: 70px auto;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(4px);
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        textarea {
            height: 200px;
            resize: vertical;
        }

        .buttons {
            text-align: center;
            margin-top: 30px;
        }

        button {
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        .cancel {
            background-color: #f44336;
        }

        .cancel:hover {
            background-color: #da190b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Berita</h1>

        <?php if (isset($error)): ?>
            <div style="color: red; margin-bottom: 20px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" value="<?= htmlspecialchars($berita['tanggal']) ?>" required>
            </div>

            <div class="form-group">
                <label for="judul">Judul Berita</label>
                <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($berita['judul']) ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" required><?= htmlspecialchars($berita['deskripsi']) ?></textarea>
            </div>

            <div class="buttons">
                <button type="submit">Simpan</button>
                <button type="button" class="cancel" onclick="window.location.href='detail_berita.php?id=<?= $berita['id'] ?>'">Batal</button>
            </div>
        </form>
    </div>
</body>
</html>