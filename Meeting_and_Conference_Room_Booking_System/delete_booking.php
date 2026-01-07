<?php
session_start();
require_once 'config.php';

// ✅ Ensure user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$username = $user['username'];

// ✅ Validate request
if (!isset($_GET['id']) || !isset($_GET['type'])) {
    die("Invalid request.");
}

$id = (int) $_GET['id'];
$type = $_GET['type'];

// ✅ Determine table safely
switch ($type) {
    case 'meeting':
        $table = 'meeting_bookings';
        break;
    case 'studio':
        $table = 'studio_bookings';
        break;
    default:
        die("Invalid booking type.");
}

// ✅ Only delete if it belongs to the logged-in user
$stmt = $pdo->prepare("DELETE FROM $table WHERE id = ? AND booked_by = ?");
$stmt->execute([$id, $username]);

// ✅ Redirect back to dashboard
header("Location: dashboard.php?status=deleted");
exit;
?>
