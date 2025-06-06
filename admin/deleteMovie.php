<?php
require_once 'auth_check.php';
session_start();

// Validate movie ID
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error'] = "Invalid movie ID";
    header("Location: movies.php");
    exit;
}

$movieId = intval($_GET['id']);

// Database connection
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);
if (!$link) {
    $_SESSION['error'] = "Database connection failed";
    header("Location: movies.php");
    exit;
}

// First get image path to delete file
$imgSql = "SELECT movieImg FROM movietable WHERE movieID = ?";
$imgStmt = mysqli_prepare($link, $imgSql);
mysqli_stmt_bind_param($imgStmt, "i", $movieId);
mysqli_stmt_execute($imgStmt);
mysqli_stmt_bind_result($imgStmt, $imgPath);
mysqli_stmt_fetch($imgStmt);
mysqli_stmt_close($imgStmt);

// Delete movie record
$deleteSql = "DELETE FROM movietable WHERE movieID = ?";
$deleteStmt = mysqli_prepare($link, $deleteSql);
mysqli_stmt_bind_param($deleteStmt, "i", $movieId);

if (mysqli_stmt_execute($deleteStmt)) {
    // Delete the image file if exists
    if (!empty($imgPath)) {
        $fullPath = "../" . $imgPath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    $_SESSION['success'] = "Movie deleted successfully";
} else {
    $_SESSION['error'] = "Error deleting movie: " . mysqli_error($link);
}

mysqli_stmt_close($deleteStmt);
mysqli_close($link);
header("Location: movies.php");
exit;
