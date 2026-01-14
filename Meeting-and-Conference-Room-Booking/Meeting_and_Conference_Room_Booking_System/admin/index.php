<?php
session_start();

// ✅ Ensure admin access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - PTV Booking System</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* ===== NEON DARK THEME ===== */
        body {
            background: radial-gradient(circle at top right, #001133, #000);
            color: #e0e6f0;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
 
        }

        .dashboard {
            text-align: center;
            background: rgba(10, 15, 35, 0.9);
            border: 1px solid rgba(0, 255, 255, 0.3);
            box-shadow: 0 0 25px rgba(0, 191, 255, 0.3);
            border-radius: 15px;
            padding: 40px;
            width: 90%;
            max-width: 700px;
            position: relative;
            overflow: hidden;
             height: 300px;
        }

        .dashboard::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 191, 255, 0.1), transparent 70%);
            animation: rotate 10s linear infinite;
            z-index: 0;
        }

        .dashboard * {
            position: relative;
            z-index: 2;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        h2 {
            color: #00bfff;
            text-shadow: 0 0 15px rgba(0, 191, 255, 0.8);
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 15px;
            color: #8fd3ff;
            opacity: 0.8;
            margin-bottom: 30px;
        }

        .btn-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            justify-content: center;
            align-items: center;
            text-decoration: none;
        }

        .btn-neon {
            background: transparent;
            color: #00ffff;
            border: 1px solid #00bfff;
            text-transform: uppercase;
            padding: 15px;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: 0.3s ease-in-out;
            box-shadow: 0 0 10px rgba(0, 191, 255, 0.2);
                text-decoration: none; /* ✅ Removes underline */
        }

        .btn-neon:hover {
            background: linear-gradient(90deg, #0077ff, #00ffff);
            color: #fff;
            transform: scale(1.05);
            box-shadow: 0 0 25px #00ffff, 0 0 45px #0077ff;
            border-color: transparent;
        }

        .logout-btn {
            margin: 55px;
            background: #0aa6e4ff;
            border: none;
            color: #fff;
            font-weight: 500;
            border-radius: 8px;
            padding: 12px 40px;
            box-shadow: 0 0 20px rgba(255, 30, 86, 0.5);
            transition: all 0.3s ease;
            text-decoration: none; /* ✅ Removes underline */
        }

        .logout-btn:hover {
            background: #ff3f6d;
            box-shadow: 0 0 25px rgba(255, 30, 86, 0.7);
            transform: scale(1.05);
        }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #4dd2ff;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Welcome, <?= htmlspecialchars($user['username']) ?>!</h2>
        <div class="subtitle">Admin Dashboard · PTV Booking System</div>

        <div class="btn-grid">
            <a href="meeting_list.php" class="btn-neon"> Meeting Booking</a>
            <a href="studio_list.php" class="btn-neon"> Studio Booking</a>
            <a href="users.php" class="btn-neon"> Manage Users</a>
        </div>
        <div style="margin-top: 20px;">
        <a href="../login.php" class="logout-btn">Logout</a>
        </div>
        <footer>© <?= date('Y') ?> PTV Booking System</footer>
    </div>
</body>
</html>
