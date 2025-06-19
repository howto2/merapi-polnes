<?php
require_once('../koneksi.php');
session_start();

// Cek apakah user sudah login sebagai member
if (!isset($_SESSION['username'])) {
    header('Location: login_member.php');
    exit;
}

// Ambil data dari form
$tanggal = $_POST['tanggal'];
$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];

// Inisialisasi array untuk menyimpan nama file foto
$foto_names = array();

// Proses upload foto
if(isset($_FILES['foto'])) {
    $total_files = count($_FILES['foto']['name']);
    
    // Batasi maksimal 3 foto
    $max_files = min($total_files, 3);
    
    for($i = 0; $i < $max_files; $i++) {
        if($_FILES['foto']['error'][$i] == 0) {
            $file_name = time() . '_' . $_FILES['foto']['name'][$i];
            $target_path = '../uploads/' . $file_name;
            
            // Pindahkan file yang diupload
            if(move_uploaded_file($_FILES['foto']['tmp_name'][$i], $target_path)) {
                $foto_names[] = $file_name;
            }
        }
    }
}

// Gabungkan nama file foto dengan separator koma
$foto_string = implode(',', $foto_names);

// Ambil member_id dari database berdasarkan username di session
$username = $_SESSION['username'];
$query = "SELECT id FROM member_login WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$member_id = $row['id'];

// Masukkan data ke database
try {
    $sql = "INSERT INTO berita (judul, deskripsi, foto, tanggal, member_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $judul, $deskripsi, $foto_string, $tanggal, $member_id);
    
    if($stmt->execute()) {
        // Redirect ke halaman sukses
        header('Location: member_dashboard.php?status=success');
        exit;
    } else {
        throw new Exception("Gagal menyimpan data");
    }
} catch (Exception $e) {
    // Redirect ke halaman error
    header('Location: update_berita.php?status=error&message=' . urlencode($e->getMessage()));
    exit;
}
?>