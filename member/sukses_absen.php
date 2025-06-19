<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: proses_absen.php");
    exit();
}
$nama_user = $_SESSION['nama_user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Berhasil</title>
    <style>
        body {
            margin: 0;
            background: linear-gradient(to bottom right, #38bdf8, #2563eb);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            color: white;
        }

        .popup {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 40px 50px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            animation: fadeIn 0.6s ease-out;
        }

        .popup h2 {
            font-size: 28px;
            margin-bottom: 15px;
            color: #10b981;
        }

        .checkmark {
            font-size: 60px;
            color: #10b981;
            animation: bounce 0.5s ease;
        }

        @keyframes bounce {
            0%   { transform: scale(0.5); opacity: 0; }
            50%  { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to   { opacity: 1; transform: scale(1); }
        }

        .popup p {
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="popup">
        <div class="checkmark">✔</div>
        <h2>Halo, <?= htmlspecialchars($nama_user) ?>!</h2>
        <p>Absensimu berhasil disimpan 🎉</p>
        <p>Kamu akan dialihkan ke dashboard sebentar lagi...</p>
    </div>

    <script>
        setTimeout(function() {
            window.location.href = "member_dashboard.php";
        }, 4000);
    </script>
</body>
</html>