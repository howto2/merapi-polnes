<?php
session_start();
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header("Location: member.html?error=kosong");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM member_login WHERE username = ?");
    if (!$stmt) {
        die("Query error: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Ganti dengan password_verify jika password di-hash
        if ($row['password'] === $password) {
            $_SESSION['username'] = $username;
            $_SESSION['nama_user'] = $row['nama'];
            header("Location: member_dashboard.php");
            exit();
        } else {
            header("Location: member.html?error=password");
            exit();
        }
    } else {
        header("Location: member.html?error=username");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
