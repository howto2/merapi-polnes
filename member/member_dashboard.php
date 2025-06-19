<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$nama_user = $_SESSION['nama_user']; // Ubah sesuai variabel yang kamu simpan
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Member</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            margin: 0;
            background: url('../img/mount4.png') no-repeat center center fixed;
            background-size: cover;
        }

        .main-content {
            padding: 120px 20px 20px 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .welcome {
            color: #fff;
            font-size: 48px;
            font-weight: 600;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
            text-align: center;
            width: 100%;
        }

        /* Tambahan fix posisi logout ke bawah sidebar */
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
            <a href="member_dashboard.php">Dashboard</a>
            <a href="absensi_piket.php">Absensi Piket</a>
            <a href="update_berita.php">Update Berita</a>
        </div>
        <a href="../logout.php" class="logout-link">Logout</a>
    </div>

    <!-- Navbar -->
    <div class="navbar-container" id="navbarContainer">
        <div class="navbar" id="navbar">
            <div class="hamburger" onclick="toggleMenu()">☰</div>
            <h3 class="brand">MERAPI POLNES</h3>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="welcome">
            Halo, Selamat Datang <?= htmlspecialchars($nama_user) ?>!
        </div>
    </div>

</body>
</html>
