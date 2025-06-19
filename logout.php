<?php
session_start();
session_unset();     // Menghapus semua variabel session
session_destroy();   // Menghancurkan session

// Redirect ke halaman index.html
header("Location: index.php");
exit();
