<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "dbweb_mrp";

// Gunakan gaya OOP
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
