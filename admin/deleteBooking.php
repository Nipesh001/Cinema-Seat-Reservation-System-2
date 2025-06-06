<?php
require_once 'auth_check.php';
session_start();

// Validate booking ID
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error'] = "Invalid booking ID";
    header("Location: bookings.php");
    exit;
}

$bookingId = intval($_GET['id']);

// Database connection
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);
if (!$link) {
    $_SESSION['error'] = "Database connection failed";
    header("Location: bookings.php");
    exit;
}

// Check if booking exists
$checkSql = "SELECT bookingID FROM bookingtable WHERE bookingID = ?";
$checkStmt = mysqli_prepare($link, $checkSql);
mysqli_stmt_bind_param($checkStmt, "i", $bookingId);
mysqli_stmt_execute($checkStmt);
mysqli_stmt_store_result($checkStmt);

if (mysqli_stmt_num_rows($checkStmt) === 0) {
    $_SESSION['error'] = "Booking not found";
    header("Location: bookings.php");
    exit;
}
mysqli_stmt_close($checkStmt);

// Delete booking
$deleteSql = "DELETE FROM bookingtable WHERE bookingID = ?";
$deleteStmt = mysqli_prepare($link, $deleteSql);
mysqli_stmt_bind_param($deleteStmt, "i", $bookingId);

if (mysqli_stmt_execute($deleteStmt)) {
    $_SESSION['success'] = "Booking #$bookingId deleted successfully";
} else {
    $_SESSION['error'] = "Error deleting booking: " . mysqli_error($link);
}

mysqli_stmt_close($deleteStmt);
mysqli_close($link);
header("Location: bookings.php");
exit;
