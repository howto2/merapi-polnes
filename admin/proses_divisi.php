<?php
require_once('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    
    // Handle file upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $foto = $_FILES['foto'];
        $foto_name = time() . '_' . $foto['name'];
        $upload_dir = '../uploads/divisi/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($foto['tmp_name'], $upload_dir . $foto_name)) {
            // Insert into database
            $query = "INSERT INTO divisi (nama, deskripsi, foto) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $nama, $deskripsi, $foto_name);
            
            if ($stmt->execute()) {
                header('Location: kelola_divisi.php?status=success');
                exit;
            } else {
                header('Location: kelola_divisi.php?status=error');
                exit;
            }
        }
    }
} elseif ($_GET['action'] === 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get foto name before deleting
    $query = "SELECT foto FROM divisi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Delete from database
    $query = "DELETE FROM divisi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Delete foto file
        if ($row && !empty($row['foto'])) {
            $foto_path = '../uploads/divisi/' . $row['foto'];
            if (file_exists($foto_path)) {
                unlink($foto_path);
            }
        }
        header('Location: kelola_divisi.php?status=success');
        exit;
    }
}

header('Location: kelola_divisi.php?status=error');
exit;