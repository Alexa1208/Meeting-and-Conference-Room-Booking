<?php
include '../config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$message = '';
$editMode = false;
$booking = [
    'room' => '',
    'date' => '',
    'start_time' => '',
    'end_time' => '',
    'purpose' => ''
];

// ✅ If editing, fetch existing booking
if (isset($_GET['id'])) {
    $editMode = true;
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM meeting_bookings WHERE id = ?");
    $stmt->execute([$id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking) {
        $message = "⚠️ Booking not found.";
        $editMode = false;
    }
}

// ✅ Handle form submission — PLACE YOUR CODE HERE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room = $_POST['room'];
    $date = $_POST['date'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $purpose = $_POST['purpose'];
    $booked_by = $_SESSION['user']['username'] ?? 'Unknown User'; // ✅ fixed

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE meeting_bookings 
                               SET room = ?, date = ?, start_time = ?, end_time = ?, purpose = ?, booked_by = ?
                               WHERE id = ?");
        $stmt->execute([$room, $date, $start, $end, $purpose, $booked_by, $_POST['id']]);
        $message = "✅ Booking updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO meeting_bookings (room, date, start_time, end_time, purpose, booked_by)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$room, $date, $start, $end, $purpose, $booked_by]);
        $message = "✅ Meeting Room Booked Successfully!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $editMode ? 'Edit Booking' : 'Book Meeting Room' ?> | Booking System</title>
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
            width: 50%;
            position: relative;
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
            padding: 35px 40px;
            width: 450px;
            
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
    color: #121313ff;
    font-weight: 500;
    display: block;
    width: 30%;          /* match the input width */
    margin-left:52px;  /* center label */
    text-align: center;
    background: rgba(0, 191, 255, 0.98);
    padding: 10px 5px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}
input.form-control,
textarea.form-control,
select.form-control {
    background-color: #0f172a;
    border: 1px solid #00bfff;
    color: #e0e6f0;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 20px;
    transition: all 0.3s ease-in-out;
    width: 80%;          /* keep 50% width */
    margin: 0 auto 20px; /* center horizontally */
    display: block;      /* ensures centering works */
    text-align: left;
}
        input.form-control:focus,
        textarea.form-control:focus,
        select.form-control:focus {
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
    <h3><?= $editMode ? 'Edit Meeting Booking' : 'Book Meeting Room' ?></h3>

    <?php if (!empty($message)) echo "<div class='alert alert-success'>$message</div>"; ?>

    <form method="POST" class="card">
        <?php if ($editMode): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($booking['id']) ?>">
        <?php endif; ?>

        <label>Room Name</label>
        <input type="text" name="room" class="form-control" value="<?= htmlspecialchars($booking['room']) ?>" required>

        <label>Date</label>
        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($booking['date']) ?>" required>

        <label>Start Time</label>
        <input type="time" name="start_time" class="form-control" value="<?= htmlspecialchars($booking['start_time']) ?>" required>

        <label>End Time</label>
        <input type="time" name="end_time" class="form-control" value="<?= htmlspecialchars($booking['end_time']) ?>" required>
    
        <label>Purpose</label>
        <textarea name="purpose" rows="3" class="form-control" required><?= htmlspecialchars($booking['purpose']) ?></textarea>

        <div class="button-row">
            <button type="submit" class="btn btn-primary"><?= $editMode ? 'Update Booking' : 'Book Room' ?></button>
            <a href="../dashboard.php" class="btn btn-secondary">Back</a>
        </div>
    </form>

    <div class="footer-text">© <?= date('Y') ?> Booking System</div>
</div>
</body>
</html>
