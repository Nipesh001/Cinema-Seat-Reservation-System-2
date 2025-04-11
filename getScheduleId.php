<?php
session_start();
require_once 'includes/layout.php';

$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);

// Get parameters
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';
$theatre = $_GET['theatre'] ?? '';
$movieId = $_GET['movieId'] ?? 0;

// Get schedule ID based on parameters
$query = "SELECT scheduleID FROM scheduletable 
         WHERE movieID = ? 
         AND scheduleDate = ? 
         AND scheduleTime = ? 
         AND theatre = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "isss", $movieId, $date, $time, $theatre);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$schedule = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
if ($schedule) {
    echo json_encode(['scheduleId' => $schedule['scheduleID']]);
} else {
    echo json_encode(['error' => 'Schedule not found']);
}
?>
