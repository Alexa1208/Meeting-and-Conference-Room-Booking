<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$user = $_SESSION['user']['username'];
$message = '';
$editMode = false;
$booking = [
    'id' => '',
    'studio' => '',
    'type' => '',
    'date' => '',
    'start_time' => '',
    'end_time' => '',
    'project' => ''
];

// ✅ Check if we're editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM studio_bookings WHERE id = ?");
    $stmt->execute([$id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($booking) $editMode = true;
}

// ✅ Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $studio = $_POST['studio'];
    $type = $_POST['type'] ?? '';
    $date = $_POST['date'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $project = $_POST['project'];

    if (!empty($id)) {
        // ✅ UPDATE existing record
        $stmt = $pdo->prepare("UPDATE studio_bookings 
                               SET studio=?, type=?, date=?, start_time=?, end_time=?, project=?
                               WHERE id=? AND booked_by=?");
        $stmt->execute([$studio, $type, $date, $start, $end, $project, $id, $user]);
        $message = "✅ Booking updated successfully!";
    } else {
        // ✅ INSERT new record
        $stmt = $pdo->prepare("INSERT INTO studio_bookings (studio, type, date, start_time, end_time, project, booked_by)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$studio, $type, $date, $start, $end, $project, $user]);
        $message = "✅ Booking saved successfully!";
    }

    // ✅ Redirect to avoid double submit
    header("Location: book_studio.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $editMode ? 'Edit Studio Booking' : 'Book Studio' ?> | Booking System</title>
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


        input.form-control:focus,
        textarea.form-control:focus,
        select.form-control:focus {
            outline: none;
            box-shadow: 0 0 15px #00bfff;
            border-color: #00bfff;
        }

        .row {
            display: flex;
            gap: 20px;
        }

        .col-half {
            flex: 1;
        }

        .btn-success {
            background: linear-gradient(90deg, #1556a1ff, #58c6e7ff);
            border: none;
            color: #e4dedeff;
            font-weight: bold;
            text-transform: uppercase;
            padding: 12px;
            border-radius: 8px;
            width: 40%;
            box-shadow: 0 0 20px rgba(0, 255, 200, 0.5);
            transition: all 0.3s ease-in-out;
            margin-left: 130px;
        }

        .btn-success:hover {
            box-shadow: 0 0 25px #00ffff, 0 0 50px #00ffaa;
            transform: scale(1.05);
        }

        .btn-secondary {
            background: transparent;
            color: #80dfff;
            border: 1px solid #00bfff;
            border-radius: 8px;
            padding: 12px;
            width: 35%;
            font-weight: bold;
            text-transform: uppercase;
            transition: all 0.3s ease-in-out;
            margin-left: 130px;
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
        }

        .button-row {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
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
    <h3><?= $editMode ? 'Edit Studio Booking' : 'Studio Utilization & Booking' ?></h3>

    <?php if (!empty($message)) echo "<div class='alert alert-success'>$message</div>"; ?>

    <form method="POST" class="card">
        <?php if ($editMode): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($booking['id']) ?>">
        <?php endif; ?>

        <label>Studio Name</label>
        <select name="studio" class="form-control" required>
            <option value="" disabled <?= empty($booking['studio']) ? 'selected' : '' ?>>Select studio...</option>
            <option value="Studio A" <?= ($booking['studio'] === 'Studio A') ? 'selected' : '' ?>>Studio A</option>
            <option value="Studio A (Control Room)" <?= ($booking['studio'] === 'Studio A (Control Room)') ? 'selected' : '' ?>>Studio A (Control Room)</option>
            <option value="Studio B" <?= ($booking['studio'] === 'Studio B') ? 'selected' : '' ?>>Studio B</option>
            <option value="Studio B (Control Room)" <?= ($booking['studio'] === 'Studio B (Control Room)') ? 'selected' : '' ?>>Studio B (Control Room)</option>
            <option value="Editing Suite" <?= ($booking['studio'] === 'Editing Suite') ? 'selected' : '' ?>>Editing Suite</option>
        </select><br>

        <label>Type of Session</label>
        <select name="type" class="form-control" required>
            <option value="" disabled <?= empty($booking['type']) ? 'selected' : '' ?>>Select type...</option>
            <option value="Recording" <?= ($booking['type'] === 'Recording') ? 'selected' : '' ?>>Recording</option>
            <option value="Mixing" <?= ($booking['type'] === 'Mixing') ? 'selected' : '' ?>>Mixing</option>
            <option value="Editing" <?= ($booking['type'] === 'Editing') ? 'selected' : '' ?>>Editing</option>
            <option value="Rehearsal" <?= ($booking['type'] === 'Rehearsal') ? 'selected' : '' ?>>Rehearsal</option>
        </select><br>

            <div class="col-half">
                <label>Start Time</label>
                <input type="time" name="start_time" class="form-control" value="<?= htmlspecialchars($booking['start_time']) ?>" required>
            </div>
            
        <div class="col-half">
                <label>End Time</label>
                <input type="time" name="end_time" class="form-control" value="<?= htmlspecialchars($booking['end_time']) ?>" required>
            </div>
    
            <div class="col-half">
                <label>Date</label>
                <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($booking['date']) ?>" required>
            </div>
    
        <label>Project / Purpose</label>
        <textarea name="project" rows="3" class="form-control" placeholder="Describe the project or purpose..." required><?= htmlspecialchars($booking['project']) ?></textarea>


        
        <div class="button-row">
            <button type="submit" class="btn btn-success"><?= $editMode ? 'Update Booking' : 'Book Studio' ?></button>
        </div>
        <div class="button-row">
            <a href="../dashboard.php" class="btn btn-secondary">Back</a>
        </div>
    </form>

    <div class="footer-text">
        © <?= date('Y') ?> Booking System
    </div>
</div>
</body>
</html>
