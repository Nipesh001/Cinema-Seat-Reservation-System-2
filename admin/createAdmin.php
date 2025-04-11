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

    // Insert new admin
    $query = "INSERT INTO adminTable (adminUsername, adminPassword, adminEmail, adminFullName) 
              VALUES ('$username', '$password', '$email', '$fullname')";
    
    if (mysqli_query($link, $query)) {
        $_SESSION['success'] = "Admin created successfully!";
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
?>
