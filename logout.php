<?php
session_start();
require_once 'includes/auth_secure.php';

// Clear remember me cookie if set
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    
    // Delete token from database
    $stmt = $conn->prepare("DELETE FROM auth_tokens WHERE token_hash = ?");
    $hashed_token = password_hash($token, PASSWORD_DEFAULT);
    $stmt->bind_param("s", $hashed_token);
    $stmt->execute();
    
    // Clear cookie
    setcookie('remember_token', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to home page with logout success message
header("Location: index.php?logout=success");
exit();
?>
