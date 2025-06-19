<?php
session_start();
include '../koneksi.php'; // Pastikan ini mendefinisikan $conn (OOP-style)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';

    if (empty($nama) || empty($tanggal) || empty($keterangan)) {
        die("Semua field harus diisi.");
    }

    // Validasi bukti wajib diunggah
    if (!isset($_FILES['bukti']) || $_FILES['bukti']['error'] !== UPLOAD_ERR_OK) {
        die("Anda wajib melampirkan bukti sebelum mengirim absen.");
    }

    // Proses upload
    $targetDir = "../admin/uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $fileName = basename($_FILES["bukti"]["name"]);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];

    if (!in_array($fileExt, $allowedTypes)) {
        die("Jenis file tidak diizinkan. Hanya jpg, jpeg, png, dan pdf.");
    }

    $newFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
    $targetFilePath = $targetDir . $newFileName;

    if (!move_uploaded_file($_FILES["bukti"]["tmp_name"], $targetFilePath)) {
        die("Gagal mengunggah file.");
    }

    $bukti = $targetFilePath;

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO absensi (nama, tanggal, keterangan, bukti) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $tanggal, $keterangan, $bukti);

    if ($stmt->execute()) {
        header("Location: sukses_absen.php");
        exit();
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
