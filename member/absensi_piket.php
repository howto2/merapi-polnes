<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$nama = $_SESSION['nama_user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Piket Harian</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            margin: 0;
            background: url('../img/mount4.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Georgia', serif;
            color: black;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 120px 20px 20px 20px;
            min-height: 100vh;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.6);
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 600px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #eee;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .radio-group {
            display: flex;
            flex-direction: column;
        }

        .radio-group label {
            font-weight: normal;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 10px 80px;
            cursor: pointer;
            background-color: #eee;
            color: black;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease;
        }

        .custom-file-upload:hover {
            background-color: rgb(105, 104, 104);
            color: white;
        }

        input[type="file"] {
            display: none;
        }

        #file-name {
            margin-top: 10px;
            font-size: 14px;
            color: #333;
            font-style: italic;
        }

        .submit-btn {
            display: block;
            margin: 30px auto 0;
            padding: 12px 30px;
            background-color: #eee;
            color: black;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: rgb(105, 104, 104);
            color: white;
        }

        /* Sidebar style */
        .sidebar {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            width: 250px;
            height: 100vh;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 999;
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-links {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .sidebar a {
            text-decoration: none;
            color: black;
            font-size: 16px;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }

        .logout-link {
            text-decoration: none;
            color: black;
            font-size: 16px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .logout-link:hover {
            text-decoration: underline;
        }

        /* Navbar */
        .navbar-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255,255,255,0.9);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px 20px;
            transition: transform 0.3s ease;
        }

        .navbar.shifted {
            transform: translateX(250px);
        }

        .hamburger {
            font-size: 24px;
            cursor: pointer;
            user-select: none;
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
            margin-left: 10px;
            color: white;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Popup warning */
        #popup-warning {
            display: none;
            margin-top: 20px;
            padding: 15px;
            background-color: #ffdddd;
            color: #a94442;
            border: 1px solid #a94442;
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeMenu()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-links">
            <a href="member_dashboard.php">Dashboard</a>
            <a href="absensi_piket.php">Absensi Piket</a>
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

    <!-- Form Content -->
    <div class="main-content">
        <div class="form-container">
            <h2>Form Absensi Piket Harian</h2>
            <form action="proses_absen.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>" required>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal Piket</label>
                    <input type="date" id="tanggal" name="tanggal" required>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <div class="radio-group">
                        <label><input type="radio" name="keterangan" value="Hadir" required> Hadir</label>
                        <label><input type="radio" name="keterangan" value="Izin"> Izin</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bukti">Bukti</label>
                    <label for="bukti" class="custom-file-upload">Pilih File</label>
                    <input type="file" id="bukti" name="bukti" onchange="showFileName(this)">
                    <div id="file-name">* Belum ada file dipilih</div>
                </div>

                <div id="popup-warning">Anda wajib melampirkan bukti foto sebelum mengirim absen.</div>

                <button type="submit" class="submit-btn">Kirim</button>
            </form>
        </div>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById("sidebar").classList.toggle("active");
            document.getElementById("overlay").classList.toggle("active");
            document.getElementById("navbar").classList.toggle("shifted");
        }

        function closeMenu() {
            document.getElementById("sidebar").classList.remove("active");
            document.getElementById("overlay").classList.remove("active");
            document.getElementById("navbar").classList.remove("shifted");
        }

        function showFileName(input) {
            const fileName = input.files.length > 0 ? input.files[0].name : "Belum ada file dipilih";
            document.getElementById("file-name").textContent = fileName;
        }

        function validateForm() {
            const fileInput = document.getElementById('bukti');
            const popup = document.getElementById('popup-warning');

            if (!fileInput.files.length) {
                popup.style.display = 'block';

                setTimeout(() => {
                    popup.style.display = 'none';
                }, 3000);

                return false;
            }
            return true;
        }
    </script>
</body>
</html>
