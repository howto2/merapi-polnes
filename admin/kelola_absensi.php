<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../koneksi.php';

// Ambil parameter filter dari form
$filterNama = $_GET['nama'] ?? '';
$filterTanggal = $_GET['tanggal'] ?? '';

// Query dasar
$query = "SELECT * FROM absensi WHERE 1=1";

// Tambahkan kondisi jika ada filter
if (!empty($filterNama)) {
    $query .= " AND nama LIKE '%" . $conn->real_escape_string($filterNama) . "%'";
}

if (!empty($filterTanggal)) {
    $query .= " AND tanggal = '" . $conn->real_escape_string($filterTanggal) . "'";
}

$query .= " ORDER BY tanggal DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Absensi Piket</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            margin: 0;
            background: url('../img/mount4.png') no-repeat center center fixed;
            background-size: cover;
        }

        .main-content {
            margin: 100px auto 40px; /* Spasi atas 100px */
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
            background-color:rgb(0, 58, 120);
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
            background-color:rgb(117, 117, 117);
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
    <h2>Data Absensi Piket</h2>

    <form class="filter-form" method="get">
        <input type="text" name="nama" placeholder="Filter Nama" value="<?= htmlspecialchars($filterNama) ?>">
        <input type="date" name="tanggal" value="<?= htmlspecialchars($filterTanggal) ?>">
        <button type="submit">Terapkan Filter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Bukti</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['waktu_input']) ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td>
                        <a href="<?= htmlspecialchars($row['bukti']) ?>" target="_blank">Lihat</a> |
                        <a href="edit_absensi.php?id=<?= $row['id'] ?>">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Tidak ada data ditemukan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function toggleMenu() {
        document.querySelector(".sidebar").classList.toggle("active");
    }
</script>

</body>
</html>