<?php
require_once 'auth_check.php';

// Only super admins can create other admins
if (!($_SESSION['isSuperAdmin'] ?? false)) {
    $_SESSION['error'] = "Unauthorized access!";
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);

    // Sanitize inputs
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $fullname = mysqli_real_escape_string($link, $_POST['fullname']);
    $isSuperAdmin = isset($_POST['isSuperAdmin']) ? intval($_POST['isSuperAdmin']) : 0;

    // Insert new admin
    $query = "INSERT INTO admintable (adminUsername, adminPassword, adminEmail, adminFullName, isSuperAdmin) 
              VALUES ('$username', '$password', '$email', '$fullname', $isSuperAdmin)";

    if (mysqli_query($link, $query)) {
        require_once '../includes/popup.php';
        showPopup('Admin created successfully!');
        echo "<script>setTimeout(() => { window.location.href='dashboard.php'; }, 2100);</script>";
        exit();
    } else {
        $_SESSION['error'] = "Error creating admin: " . mysqli_error($link);
    }

    mysqli_close($link);
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
