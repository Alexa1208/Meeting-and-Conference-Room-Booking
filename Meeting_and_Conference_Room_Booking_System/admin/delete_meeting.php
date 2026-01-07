<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM meeting_bookings WHERE id = ?");
$stmt->execute([$id]);

header("Location: meeting_list.php");
exit;
?>
