<?php
session_start();

// Skip verification if we're already on the login page
$current_page = basename($_SERVER['PHP_SELF']);
if ($current_page === 'adminLogin.php') {
    return;
}

// Redirect to login if not authenticated
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
    header('Location: adminLogin.php');
    exit;
}

// Verify admin status if logged in
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);
$adminId = $_SESSION['admin_id'] ?? 0;
$query = "SELECT isSuperAdmin FROM admintable WHERE adminID = $adminId";
$result = mysqli_query($link, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $_SESSION['isSuperAdmin'] = $row['isSuperAdmin'];
} else {
    session_destroy();
    header("Location: adminLogin.php");
    exit;
}

mysqli_close($link);
