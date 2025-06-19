<?php
require_once('../koneksi.php');
session_start();

// Proteksi login admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_member.php');
    exit;
}

// Filter (opsional)
$filter_nama = $_GET['nama'] ?? '';
$filter_tanggal = $_GET['tanggal'] ?? '';

$query = "SELECT b.*, m.nama as nama_member 
FROM berita b 
LEFT JOIN member_login m ON b.member_id = m.id 
WHERE 1=1";

if ($filter_nama != '') {
    $query .= " AND b.judul LIKE '%" . $conn->real_escape_string($filter_nama) . "%'";
}
if ($filter_tanggal != '') {
    $query .= " AND DATE(b.tanggal) = '" . $conn->real_escape_string($filter_tanggal) . "'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Berita</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            margin: 0;
            background: url('../img/mount4.png') no-repeat center center fixed;
            background-size: cover;
        }

        .main-content {
            margin: 100px auto 40px;
            padding: 30px 20px;
            max-width: 900px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.8);
        }

        h2 {
            text-align: center;
            font-size: 26px;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-bottom: 25px;
            background-color: #f9f9f9;
            padding: 15px 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .filter-form input[type="text"],
        .filter-form input[type="date"] {
            padding: 8px 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 180px;
            background-color: #fff;
        }

        .filter-form button {
            padding: 8px 16px;
            background-color: #007BFF;
            color: white;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .filter-form button:hover {
            background-color: rgb(0, 58, 120);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            background-color: white;
        }

        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid #eaeaea;
            text-align: center;
            font-size: 14px;
            color: #333;
        }

        th {
            background-color: rgb(117, 117, 117);
            color: white;
        }

        tr:hover {
            background-color: #f2f7ff;
        }

        td a {
            color: #007BFF;
            text-decoration: none;
            font-weight: 500;
        }

        td a:hover {
            text-decoration: underline;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: -250px;
            height: 100vh;
            width: 250px;
            background: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease;
            z-index: 1000;
        }

        .sidebar-links {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .logout-link {
            text-decoration: none;
            color: black;
            font-size: 16px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            margin-top: auto;
        }

        .logout-link:hover {
            text-decoration: underline;
        }

        .action-links a {
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            margin: 0 2px;
        }

        .action-links .publish {
            background-color: #28a745;
            color: white;
        }

        .action-links .delete {
            background-color: #dc3545;
            color: white;
        }

        .action-links a:hover {
            opacity: 0.8;
        }
    </style>

    <script>
        function toggleMenu() {
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("overlay");
            const navbarContainer = document.getElementById("navbarContainer");

            sidebar.classList.toggle("active");
            overlay.classList.toggle("active");
            navbarContainer.classList.toggle("shifted");
        }

        function closeMenu() {
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("overlay");
            const navbarContainer = document.getElementById("navbarContainer");

            sidebar.classList.remove("active");
            overlay.classList.remove("active");
            navbarContainer.classList.remove("shifted");
        }

        function confirmDelete(id) {
            return confirm('Yakin ingin menghapus berita ini?');
        }
    </script>
</head>
<body>

<!-- Overlay -->
<div class="overlay" id="overlay" onclick="closeMenu()"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-links">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="kelola_absensi.php">Kelola Absensi</a>
        <a href="kelola_berita.php">Kelola Berita</a>
    </div>
    <a href="../logout.php" class="logout-link">Logout</a>
</div>

<!-- Navbar -->
<div class="navbar-container" id="navbarContainer">
    <div class="navbar" id="navbar">
        <div class="hamburger" onclick="toggleMenu()">☰</div>
        <h3 class="brand">MERAPI POLNES - ADMIN</h3>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2>Daftar Berita</h2>

        <form class="filter-form" method="GET">
            <input type="text" name="nama" placeholder="Cari judul berita..." value="<?= htmlspecialchars($filter_nama) ?>">
            <input type="date" name="tanggal" value="<?= htmlspecialchars($filter_tanggal) ?>">
            <button type="submit">Terapkan Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Judul Berita</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_member']) ?></td>
                            <td><a href="detail_berita.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['judul']) ?></a></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td class="action-links">
                                <a href="publish_berita.php?id=<?= $row['id'] ?>" class="publish"><?= $row['status'] == 'published' ? 'Unpublish' : 'Publish' ?></a> |
                                <a href="hapus_berita.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirmDelete(<?= $row['id'] ?>)">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Tidak ada data berita.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>