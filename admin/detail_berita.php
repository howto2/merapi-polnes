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

// Query untuk mengambil detail berita beserta nama member
$query = "SELECT b.*, m.nama as nama_member 
          FROM berita b 
          LEFT JOIN member_login m ON b.member_id = m.id 
          WHERE b.id = ?";
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
  <title><?= htmlspecialchars($berita['judul']) ?></title>
  <link rel="stylesheet" href="../style.css">
  <style>
    body {
      margin: 0;
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
      position: relative;
      text-align: center;
    }

    .meta-info {
      position: absolute;
      top: 20px;
      left: 20px;
      color: #666;
      font-size: 14px;
      text-align: left;
    }

    h1 {
      font-size: 26px;
      margin-top: 40px;
      margin-bottom: 20px;
      color: #393737;
    }

    p.deskripsi {
      font-size: 16px;
      margin-bottom: 30px;
      line-height: 1.6;
      color: #393737;
      text-align: center;
    }

    .foto-container {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }

    .meta-info {
      margin-bottom: 20px;
      color: #666;
      font-size: 14px;
    }
    
    .foto-container img {
      max-width: 300px;
      height: auto;
      object-fit: contain;
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .actions {
      margin-top: 20px;
    }

    .actions a {
      color: #0066cc;
      text-decoration: none;
      margin: 0 10px;
    }

    .actions a:hover {
      text-decoration: underline;
    }

    .sidebar {
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
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .sidebar-links {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .sidebar-links a {
        color: #333;
        text-decoration: none;
        padding: 10px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .sidebar-links a:hover {
        background-color: #f0f0f0;
    }

    .logout-container {
        margin-top: auto;
        border-top: 1px solid #ccc;
        padding-top: 10px;
    }

    .logout-link {
        text-decoration: none;
        color: black;
        font-size: 16px;
        display: block;
        padding: 10px;
    }

    .logout-link:hover {
        text-decoration: underline;
    }
  </style>
</head>
<body>
  <!-- Overlay -->
  <div class="overlay" id="overlay" onclick="closeMenu()"></div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="kelola_absensi.php">Kelola Absensi</a>
    <a href="kelola_berita.php">Kelola Berita</a>
    <a href="../logout.php" style="margin-top: auto;">Logout</a>
  </div>

  <!-- Navbar -->
  <div class="navbar-container" id="navbarContainer">
    <div class="navbar">
      <div class="hamburger" onclick="toggleMenu()">☰</div>
      <h3 class="brand">MERAPI POLNES</h3>
    </div>
  </div>

  <!-- Content Container -->
  <div class="container">
    <h1><?= htmlspecialchars($berita['judul']) ?></h1>
    
    <div class="meta-info">
      <p>Ditulis oleh: <?= htmlspecialchars($berita['nama_member']) ?></p>
      <p>Tanggal: <?= date('d/m/Y', strtotime($berita['tanggal'])) ?></p>
    </div>

    <p class="deskripsi"><?= nl2br(htmlspecialchars($berita['deskripsi'])) ?></p>

    <div class="foto-container">
      <?php 
      if (!empty($berita['foto'])) {
          $foto_array = explode(',', $berita['foto']);
          foreach ($foto_array as $foto) {
              if (!empty($foto)) {
                  echo '<img src="../uploads/' . htmlspecialchars(trim($foto)) . '" alt="Foto Berita">';
              }
          }
      }
      ?>
    </div>

    <div class="actions">
      <a href="edit_berita.php?id=<?= $berita['id'] ?>">Edit</a> |
      <a href="kelola_berita.php">Kembali</a>
    </div>
  </div>

  <script>
    function toggleMenu() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('overlay').classList.toggle('active');
      document.getElementById('navbarContainer').classList.toggle('shifted');
    }

    function closeMenu() {
      document.getElementById('sidebar').classList.remove('active');
      document.getElementById('overlay').classList.remove('active');
      document.getElementById('navbarContainer').classList.remove('shifted');
    }
  </script>
</body>
</html>