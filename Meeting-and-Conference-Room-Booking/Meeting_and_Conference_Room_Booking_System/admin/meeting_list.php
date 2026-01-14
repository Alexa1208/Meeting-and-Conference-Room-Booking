<?php
session_start();
include '../config.php';

// ✅ Fixed session role check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user'];

// ✅ Fetch meeting bookings
$stmt = $pdo->query("SELECT * FROM meeting_bookings ORDER BY date DESC");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meeting Bookings | Admin</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ===== DARK NEON BLUE THEME ===== */
        body {
            background-color: #0a0f1f;
            background-image: radial-gradient(circle at top right, #001133, #000);
            color: #e0e6f0;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 50px;
        }

        h3 {
            color: #00bfff;
            text-shadow: 0 0 15px rgba(0, 191, 255, 0.8);
            font-weight: 600;
            margin-bottom: 35px;
        }

        .card {
            background: rgba(15, 20, 35, 0.95);
            border: 1px solid rgba(0, 255, 255, 0.3);
            box-shadow: 0 0 25px rgba(0, 191, 255, 0.2);
            border-radius: 18px;
            padding: 40px;
            width: 60%;
            margin-left:380px;
        }

        .btn-secondary {
            background: linear-gradient(90deg, #0077ff, #00ffff);
            border-radius: 8px;
            color: #050505ff;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0 0 20px rgba(0, 191, 255, 0.3);
            transition: all 0.3s ease-in-out;
            padding: 12px 40px;
        }

        .btn-secondary:hover {
            box-shadow: 0 0 25px #00ffff, 0 0 50px #0077ff;
            transform: scale(1.05);
        }

        /* ===== TABLE STYLING ===== */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        .table {
            color: #e0e6f0;
            border-collapse: separate;
            border-spacing: 0 12px;
            width: 100%;
        }

        .table thead th {
            background-color: rgba(0, 191, 255, 0.15);
            border: none;
            padding: 16px;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            background-color: rgba(15, 20, 35, 0.8);
            border-radius: 10px;
            transition: 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 191, 255, 0.1);
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 18px 14px;
            vertical-align: middle;
            border: none;
        }

        .btn-sm {
            border-radius: 8px;
            padding: 10px 14px;
            font-weight: 500;
            margin: 5px;
        }

        .btn-warning {
            background-color: rgba(0, 191, 255, 0.1);
            border: 1px solid #00ccffff;
            color: #fffbfbff;
        }

        .btn-warning:hover {
            box-shadow: 0 0 20px #ff2600ff;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: rgba(0, 191, 255, 0.1);
            border: 1px solid #00ccffff;
                        color: #fffbfbff;

        }

        .btn-danger:hover {
            box-shadow: 0 0 20px #ff1a1a;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<div class="container card mt-4">
    <h3 class="text-center">Meeting & Conference Room Bookings</h3>


    <div class="table-container">
        <table class="table text-center align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Purpose</th>
                    <th>Booked By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($bookings)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No bookings found.</td></tr>
            <?php else: ?>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['id']) ?></td>
                        <td><?= htmlspecialchars($b['room']) ?></td>
                        <td><?= htmlspecialchars($b['date']) ?></td>
                        <td><?= htmlspecialchars($b['start_time']) ?> - <?= htmlspecialchars($b['end_time']) ?></td>
                        <td><?= htmlspecialchars($b['purpose']) ?></td>
                        <td><?= htmlspecialchars($b['booked_by']) ?></td>
<td>
    <a href="edit_meeting.php?id=<?= $b['id'] ?>" 
       class="btn btn-sm btn-warning" 
       title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <a href="delete_meeting.php?id=<?= $b['id'] ?>" 
       class="btn btn-sm btn-danger" 
       onclick="return confirm('Delete this booking?')" 
       title="Delete">
        <i class="fas fa-trash-alt"></i>
    </a>
</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
        <div class="text-center mb-4">
        <a href="index.php" class="btn btn-secondary">⬅ Back</a>
    </div>
</div>
</body>
</html>
