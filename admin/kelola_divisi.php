<?php
require_once('../koneksi.php');

// Query untuk mengambil semua divisi
$query = "SELECT * FROM divisi";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Divisi - Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="admin-container">
        <h1>Kelola Divisi</h1>
        
        <!-- Form Tambah/Edit Divisi -->
        <div class="form-container">
            <h2>Tambah Divisi Baru</h2>
            <form action="proses_divisi.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama">Nama Divisi:</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi:</label>
                    <textarea id="deskripsi" name="deskripsi" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="foto">Foto Divisi:</label>
                    <input type="file" id="foto" name="foto" accept="image/*" required>
                </div>
                
                <button type="submit" name="action" value="tambah">Simpan Divisi</button>
            </form>
        </div>

        <!-- Tabel Daftar Divisi -->
        <div class="table-container">
            <h2>Daftar Divisi</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Divisi</th>
                        <th>Deskripsi</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($row = $result->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td>
                            <img src="../uploads/divisi/<?= htmlspecialchars($row['foto']) ?>" 
                                 alt="<?= htmlspecialchars($row['nama']) ?>" 
                                 style="max-width: 100px;">
                        </td>
                        <td>
                            <a href="edit_divisi.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                            <a href="proses_divisi.php?action=hapus&id=<?= $row['id'] ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Yakin ingin menghapus divisi ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>