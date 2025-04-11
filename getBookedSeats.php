<?php
header('Content-Type: application/json');
require_once 'includes/layout.php';

$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);

if (!$link) {
    die(json_encode(['error' => 'Database connection failed']));
}

$scheduleId = isset($_GET['scheduleId']) ? (int)$_GET['scheduleId'] : 0;

// Get all booked seats for this schedule
$query = "SELECT seat_number FROM seatBookings 
          WHERE schedule_id = ? AND is_booked = 1";
$stmt = mysqli_prepare($link, $query);

if (!$stmt) {
    die(json_encode(['error' => 'Database query preparation failed']));
}

mysqli_stmt_bind_param($stmt, "i", $scheduleId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$bookedSeats = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bookedSeats[] = (int)$row['seat_number'];
}

echo json_encode($bookedSeats);
mysqli_close($link);
