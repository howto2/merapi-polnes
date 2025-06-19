<?php
require_once('../koneksi.php');
session_start();

// Proteksi halaman admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_member.php');
    exit;
}

// Ambil ID berita yang akan dihapus
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        // Ambil nama file foto sebelum menghapus data
        $query = "SELECT foto FROM berita WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row) {
            // Hapus file foto jika ada
            if (!empty($row['foto'])) {
                $foto_array = explode(',', $row['foto']);
                foreach ($foto_array as $foto) {
                    $file_path = '../uploads/' . trim($foto);
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
            }
            
            // Hapus data dari database
            $query = "DELETE FROM berita WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                header('Location: kelola_berita.php?status=success&message=Berita berhasil dihapus');
                exit;
            } else {
                throw new Exception("Gagal menghapus berita");
            }
        } else {
            throw new Exception("Berita tidak ditemukan");
        }
    } catch (Exception $e) {
        header('Location: kelola_berita.php?status=error&message=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: kelola_berita.php?status=error&message=ID berita tidak valid');
    exit;
}
?>