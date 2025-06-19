<?php
session_start();
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header("Location: admin.html?error=kosong");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM admin_login WHERE username = ?");
    if (!$stmt) {
        die("Query error: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Gunakan password_verify jika password disimpan dalam bentuk hash
        if ($row['password'] === $password) {
            $_SESSION['username'] = $username;
            $_SESSION['nama_user'] = $row['nama'];
            $_SESSION['role'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            header("Location: admin.html?error=password");
            exit();
        }
    } else {
        header("Location: admin.html?error=username");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
