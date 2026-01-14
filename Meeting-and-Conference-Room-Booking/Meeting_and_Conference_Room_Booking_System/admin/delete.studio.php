<?php
session_start();
include '../config.php';
//for testing

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

try {
    $stmt = $pdo->prepare("DELETE FROM studio_bookings WHERE id = ?");
    $stmt->execute([$id]);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

header("Location: studio_list.php");
exit;
?>

