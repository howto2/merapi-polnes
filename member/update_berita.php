<?php
require_once('../koneksi.php');

// Pastikan user sudah login sebagai member
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login_member.php');
    exit;
}
$nama = $_SESSION['nama_user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Berita MERAPI POLNES</title>
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
        input[type="date"],
        textarea {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #eee;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        textarea {
            height: 150px;
            resize: vertical;
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
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #eee;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Georgia', serif;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: rgb(105, 104, 104);
            color: white;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .preview-image-container {
            position: relative;
            width: 100px;
            height: 100px;
        }

        .preview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 5px;
        }

        .remove-image {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: red;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
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

    <div class="main-content">
        <div class="form-container">
            <h2>Form Update Berita</h2>
            <form action="proses_update_berita.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" required>
                </div>
                
                <div class="form-group">
                    <label for="judul">Judul Berita</label>
                    <input type="text" id="judul" name="judul" required>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi Berita</label>
                    <textarea id="deskripsi" name="deskripsi" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Foto Berita (Max 3)</label>
                    <label class="custom-file-upload">
                        <input type="file" name="foto[]" accept="image/*" multiple required onchange="previewImages(this)">
                        Pilih File
                    </label>
                    <div id="preview-container" class="preview-container"></div>
                </div>
                
                <button type="submit" class="submit-btn">Submit</button>
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

        function updateFileName(input) {
            const fileNameDiv = document.getElementById('file-name');
            if (input.files.length > 0) {
                const fileNames = Array.from(input.files).map(file => file.name).join(', ');
                fileNameDiv.textContent = 'File terpilih: ' + fileNames;
            } else {
                fileNameDiv.textContent = '';
            }
        }

        function previewImages(input) {
            const container = document.getElementById('preview-container');
            container.innerHTML = '';
            const maxFiles = 3;
            
            if (input.files) {
                const filesArray = Array.from(input.files).slice(0, maxFiles);
                
                filesArray.forEach((file, index) => {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const imageContainer = document.createElement('div');
                        imageContainer.className = 'preview-image-container';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'preview-image';
                        
                        const removeBtn = document.createElement('div');
                        removeBtn.className = 'remove-image';
                        removeBtn.innerHTML = 'X';
                        removeBtn.onclick = function() {
                            // Hapus preview image
                            imageContainer.remove();
                            
                            // Buat FileList baru tanpa file yang dihapus
                            const dt = new DataTransfer();
                            const { files } = input;
                            
                            for(let i = 0; i < files.length; i++) {
                                if(i !== index) {
                                    dt.items.add(files[i]);
                                }
                            }
                            
                            input.files = dt.files;
                            
                            // Update required attribute jika tidak ada file
                            if(input.files.length === 0) {
                                input.required = true;
                            }
                        };
                        
                        imageContainer.appendChild(img);
                        imageContainer.appendChild(removeBtn);
                        container.appendChild(imageContainer);
                    };
                    
                    reader.readAsDataURL(file);
                });
                
                // Hapus required jika ada file yang dipilih
                if (filesArray.length > 0) {
                    input.required = false;
                }
            }
        }
    </script>
</body>
</html>
