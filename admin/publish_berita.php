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

// Cek status berita saat ini
$query = "SELECT status FROM berita WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$berita = $result->fetch_assoc();

// Toggle status
$new_status = $berita['status'] == 'published' ? 'draft' : 'published';

// Update status berita
$update_query = "UPDATE berita SET status = ? WHERE id = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param('si', $new_status, $id);

if ($stmt->execute()) {
    header('Location: kelola_berita.php?status=success&message=Status berita berhasil diubah');
} else {
    header('Location: kelola_berita.php?status=error&message=Gagal mengubah status berita');
}