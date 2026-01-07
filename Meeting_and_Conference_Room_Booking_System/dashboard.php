<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
// Fetch Meeting Room bookings
$stmt = $pdo->query("SELECT * FROM meeting_bookings ORDER BY date DESC, start_time ASC");
$meetingBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Studio bookings
$stmt2 = $pdo->query("SELECT * FROM studio_bookings ORDER BY date DESC, start_time ASC");
$studioBookings = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard | Booking System</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body {
        background-color: #050915;
        background-image: radial-gradient(circle at top right, #001133, #000);
        color: #e0e6f0;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
        margin: 0;
        padding: 40px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .main-container {
        width: 95%;
        max-width: 1100px;
        position: relative;
    }

    .logout-btn {
        position: absolute;
        top: 0;
        right: 0;
        padding: 10px 20px;
        margin: 15px;
        background: linear-gradient(90deg, #00f0f8ff, #0051ffff);
        color: white;
        font-weight: bold;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        box-shadow: 0 0 20px rgba(255, 100, 0, 0.5);
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: linear-gradient(90deg, #ff4d4d, #ffb700);
        transform: scale(1.05);
        box-shadow: 0 0 30px rgba(255, 120, 0, 0.7);
    }

    .card {
        background: rgba(10, 15, 30, 0.95);
        border: 1px solid rgba(0, 191, 255, 0.3);
        box-shadow: 0 0 25px rgba(0, 191, 255, 0.15);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0 40px rgba(0, 191, 255, 0.25);
    }

    .card h2 {
        color: #00bfff;
        font-weight: 600;
        text-shadow: 0 0 15px rgba(0, 191, 255, 0.8);
        margin-bottom: 20px;
        text-align: center;
    }

    .btn-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 15px;
        text-align: center;
    }

    .btn {
        flex: 1;
        max-width: 250px;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: bold;
        text-transform: uppercase;
        border: 1px solid #00bfff;
        background: #053a66ff;
        color: #fff;
        text-decoration: none;
        box-shadow: 0 0 15px rgba(0, 191, 255, 0.4);
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: scale(1.05);
        box-shadow: 0 0 25px rgba(0, 191, 255, 0.7);
    }

    .table {
        color: #cce7ff;
        border-collapse: collapse;
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead {
        background: linear-gradient(90deg, rgba(0,191,255,0.25), rgba(0,255,255,0.15));
        border-bottom: 2px solid rgba(0,191,255,0.4);
    }

    .table th, .table td {
        padding: 12px 14px;
        text-align: center;
        border-bottom: 1px solid rgba(0,255,255,0.1);
    }

    .table tbody tr:hover {
        background-color: rgba(0, 191, 255, 0.08);
        box-shadow: inset 0 0 10px rgba(0,255,255,0.3);
        transition: all 0.2s ease;
    }

    .table th {
        color: #00ffff;
        text-shadow: 0 0 8px rgba(0,255,255,0.6);
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        border: none;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .edit-btn {
        background: linear-gradient(90deg, #0099ff, #00ffff);
        text-decoration: none;
    }

    .edit-btn:hover {
        box-shadow: 0 0 15px rgba(0, 200, 255, 0.6);
        transform: scale(1.05);
    }

    .delete-btn {
        background: linear-gradient(90deg, #00f0f8ff, #0051ffff);
        text-decoration: none;
    }

    .delete-btn:hover {
        box-shadow: 0 0 15px rgba(255, 100, 0, 0.7);
        transform: scale(1.05);
    }

    .footer-text {
        text-align: center;
        font-size: 13px;
        color: #4dd2ff;
        opacity: 0.7;
        margin-top: 15px;
    }
    .action-btn i {
    font-size: 16px;
}

.action-btn {
    width: 36px;      /* square button */
    height: 36px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 0;
}

</style>
</head>
<body>
<div class="main-container">
    <a href="login.php" class="logout-btn">Logout</a>

    <div class="card text-center">
        <h2>Welcome, <?= htmlspecialchars($user['username']) ?>!</h2>
        <div class="btn-container">
            <a href="meeting/book_meeting.php" class="btn">Meeting & Conference Room</a>
            <a href="studio/book_studio.php" class="btn">Studio Utilization</a>
        </div>
    </div>

    <!-- Meeting Room Bookings -->
    <div class="card">
        <h2>Meeting Room Bookings</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Purpose</th>
                    <th>Booked By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($meetingBookings)): ?>
                    <tr><td colspan="7" class="no-data text-center">No meeting room bookings yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($meetingBookings as $m): ?>
    <tr>
        <td><?= htmlspecialchars($m['room']) ?></td>
        <td><?= htmlspecialchars($m['date']) ?></td>
        <td><?= htmlspecialchars(date("g:i A", strtotime($m['start_time']))) ?></td>
        <td><?= htmlspecialchars(date("g:i A", strtotime($m['end_time']))) ?></td>
        <td><?= htmlspecialchars($m['purpose']) ?></td>
        <td><?= htmlspecialchars($m['booked_by']) ?></td>
        <td>
    <?php if ($m['booked_by'] === $user['username']): ?>
        <a href="meeting/book_meeting.php?id=<?= $m['id'] ?>" 
           class="action-btn edit-btn" 
           title="Edit Booking">
            <i class="fas fa-edit"></i>
        </a>
        <a href="delete_booking.php?type=meeting&id=<?= $m['id'] ?>" 
           onclick="return confirm('Delete this meeting booking?')"
           class="action-btn delete-btn" 
           title="Delete Booking">
            <i class="fas fa-trash-alt"></i>
        </a>
    <?php else: ?>
        <span style="color: #777; font-size: 13px;">No access</span>
    <?php endif; ?>
</td>

    </tr>
<?php endforeach; ?>

                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Studio Bookings -->
    <div class="card">
        <h2>Studio Bookings</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Studio</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Project</th>
                    <th>Booked By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($studioBookings)): ?>
                    <tr><td colspan="8" class="no-data text-center">No studio bookings yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($studioBookings as $s): ?>
    <tr>
        <td><?= htmlspecialchars($s['studio']) ?></td>
        <td><?= htmlspecialchars($s['type']) ?></td>
        <td><?= htmlspecialchars($s['date']) ?></td>
        <td><?= htmlspecialchars(date("g:i A", strtotime($s['start_time']))) ?></td>
        <td><?= htmlspecialchars(date("g:i A", strtotime($s['end_time']))) ?></td>
        <td><?= htmlspecialchars($s['project']) ?></td>
        <td><?= htmlspecialchars($s['booked_by']) ?></td>
        <td>
    <?php if ($s['booked_by'] === $user['username']): ?>
        <a href="studio/book_studio.php?id=<?= $s['id'] ?>" 
           class="action-btn edit-btn" 
           title="Edit Booking">
            <i class="fas fa-edit"></i>
        </a>
        <a href="delete_booking.php?type=studio&id=<?= $s['id'] ?>" 
           onclick="return confirm('Delete this studio booking?')"
           class="action-btn delete-btn" 
           title="Delete Booking">
            <i class="fas fa-trash-alt"></i>
        </a>
    <?php else: ?>
        <span style="color: #777; font-size: 13px;">No access</span>
    <?php endif; ?>
</td>

    </tr>
<?php endforeach; ?>

                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer-text">
        © <?= date('Y') ?> Booking System Dashboard
    </div>
</div>
</body>
</html>
