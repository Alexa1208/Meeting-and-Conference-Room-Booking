<?php 
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch user by username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ✅ Verify hashed password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        if (strcasecmp($user['role'], 'admin') === 0) {
            header("Location: admin/index.php");
            exit;
        } else {
            header("Location: dashboard.php");
            exit;
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Booking System</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* ===== DARK NEON BLUE THEME ===== */
        body {
            background-color: #0a0f1f;
            background-image: radial-gradient(circle at top right, #001133, #000);
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #e0e6f0;
        }

        .card {
            background: rgba(15, 20, 35, 0.9);
            border: 1px solid rgba(0, 255, 255, 0.3);
            box-shadow: 0 0 25px rgba(0, 191, 255, 0.2), 0 0 50px rgba(0, 191, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            width: 400px;
            position: relative;
            overflow: hidden;
            z-index: 1; /* ✅ ensure content clickable */
        }

        .card * {
            position: relative;
            z-index: 2; /* ✅ brings inputs above ::before */
        }

        .card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 191, 255, 0.1), transparent 70%);
            animation: rotate 8s linear infinite;
            z-index: 0;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        h3 {
            color: #00bfff;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
            text-shadow: 0 0 10px rgba(0, 191, 255, 0.8);
        }

        input.form-control {
            background-color: #0f172a;
            border: 1px solid #00bfff;
            color: #e0e6f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            transition: all 0.3s ease-in-out;
        }

        input.form-control:focus {
            outline: none;
            box-shadow: 0 0 15px #00bfff;
            border-color: #00bfff;
        }

        .btn-primary {
            background: linear-gradient(90deg, #0077ff, #00ffff);
            border: none;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            box-shadow: 0 0 20px rgba(0, 191, 255, 0.5);
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            box-shadow: 0 0 25px #00ffff, 0 0 50px #0077ff;
            transform: scale(1.05);
        }

        .alert-danger {
            background-color: rgba(255, 0, 0, 0.15);
            border: 1px solid #ff4d4d;
            color: #ffb3b3;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }

        .footer-text {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #4dd2ff;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="card">
        <h3>Booking System Login</h3>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" autocomplete="off">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="footer-text">
            © <?= date('Y') ?> Booking System
        </div>
    </div>
</body>
</html>
