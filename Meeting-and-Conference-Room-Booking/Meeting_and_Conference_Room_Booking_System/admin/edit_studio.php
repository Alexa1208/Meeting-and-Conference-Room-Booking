<?php
session_start();
include '../config.php';

// ✅ Correct session check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ✅ Get ID and validate
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: studio_list.php");
    exit;
}

// ✅ Fetch existing booking
$stmt = $pdo->prepare("SELECT * FROM studio_bookings WHERE id = ?");
$stmt->execute([$id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    header("Location: studio_list.php");
    exit;
}

$message = '';

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studio = $_POST['studio'];
    $type = $_POST['type'];
    $date = $_POST['date'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $project = $_POST['project'];

    $update = $pdo->prepare("UPDATE studio_bookings 
                             SET studio=?, type=?, date=?, start_time=?, end_time=?, project=? 
                             WHERE id=?");
    $update->execute([$studio, $type, $date, $start, $end, $project, $id]);

    $message = "✅ Studio booking updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Studio Booking | Booking System</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body {
            background-color: #0a0f1f;
            background-image: radial-gradient(circle at top right, #001133, #000);
            font-family: 'Poppins', sans-serif;
            color: #e0e6f0;
            min-height: 100vh;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .container {
            max-width: 700px;
            width: 100%;
        }
        h3 {
            color: #00bfff;
            text-shadow: 0 0 15px rgba(0, 191, 255, 0.8);
            font-weight: 600;
            text-align: center;
            margin-bottom: 25px;
        }
        .card {
            background: rgba(15, 20, 35, 0.9);
            border: 1px solid rgba(0, 255, 255, 0.3);
            box-shadow: 0 0 25px rgba(0, 191, 255, 0.2), 0 0 50px rgba(0, 191, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 191, 255, 0.08), transparent 70%);
            animation: rotate 10s linear infinite;
            z-index: 0;
            pointer-events: none;
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        label {
            color: #80dfff;
            font-weight: 500;
            margin-bottom: 5px;
            z-index: 1;
            position: relative;
        }
        input.form-control, textarea.form-control {
            background-color: #0f172a;
            border: 1px solid #00bfff;
            color: #e0e6f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            transition: all 0.3s ease-in-out;
            z-index: 1;
            position: relative;
        }
        input.form-control:focus, textarea.form-control:focus {
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
            width: 48%;
            box-shadow: 0 0 20px rgba(0, 191, 255, 0.5);
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }
        .btn-primary:hover {
            box-shadow: 0 0 25px #00ffff, 0 0 50px #0077ff;
            transform: scale(1.05);
        }
        .btn-secondary {
            background: transparent;
            color: #80dfff;
            border: 1px solid #00bfff;
            border-radius: 8px;
            padding: 12px;
            width: 40%;
            font-weight: bold;
            text-transform: uppercase;
            transition: all 0.3s ease-in-out;
            text-align: center;
        }
        .btn-secondary:hover {
            background: #00bfff;
            color: #fff;
            box-shadow: 0 0 20px #00bfff;
            transform: scale(1.05);
        }
        .alert-success {
            background-color: rgba(0, 255, 200, 0.1);
            border: 1px solid #00ffaa;
            color: #aaffee;
            border-radius: 8px;
            text-align: center;
            font-size: 15px;
            margin-bottom: 20px;
            text-shadow: 0 0 5px rgba(0, 255, 255, 0.6);
        }
        .button-row {
            display: flex;
            justify-content: space-between;
        }
        .footer-text {
            text-align: center;
            font-size: 13px;
            color: #4dd2ff;
            opacity: 0.8;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h3>Edit Studio Booking</h3>

    <?php if (!empty($message)) echo "<div class='alert alert-success'>$message</div>"; ?>

    <form method="POST" class="card">
        <label>Studio</label>
        <input type="text" name="studio" value="<?= htmlspecialchars($booking['studio']) ?>" class="form-control" required>

        <label>Type</label>
        <input type="text" name="type" value="<?= htmlspecialchars($booking['type']) ?>" class="form-control" required>

        <label>Date</label>
        <input type="date" name="date" value="<?= htmlspecialchars($booking['date']) ?>" class="form-control" required>

        <label>Start Time</label>
        <input type="time" name="start_time" value="<?= htmlspecialchars($booking['start_time']) ?>" class="form-control" required>

        <label>End Time</label>
        <input type="time" name="end_time" value="<?= htmlspecialchars($booking['end_time']) ?>" class="form-control" required>

        <label>Project</label>
        <input type="text" name="project" value="<?= htmlspecialchars($booking['project']) ?>" class="form-control" required>

        <div class="button-row">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="studio_list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

    <div class="footer-text">© <?= date('Y') ?> Booking System</div>
</div>
</body>
</html>
