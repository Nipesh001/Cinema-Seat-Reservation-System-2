<?php
// Session configuration must be the very first thing
if (session_status() === PHP_SESSION_NONE) {
    // Secure session settings
    ini_set('session.cookie_httponly', 1);
    // Set cookie_secure only if HTTPS is used
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', 1);  // Requires HTTPS
    } else {
        ini_set('session.cookie_secure', 0);  // Allow HTTP for development
    }
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.gc_maxlifetime', 1800); // 30 minute timeout
    
    session_start();
    
    // Regenerate session ID to prevent fixation
    if (empty($_SESSION['initiated'])) {
        session_regenerate_id();
        $_SESSION['initiated'] = true;
    }
}

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cinema_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database, 3307);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for full Unicode support
$conn->set_charset("utf8mb4");

// Check for remember me cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    
    // First delete expired tokens
    $conn->query("DELETE FROM auth_tokens WHERE expires_at <= NOW()");
    
    // Find token for current user
    $stmt = $conn->prepare("SELECT t.id, t.token_hash, t.user_id, u.username 
                          FROM auth_tokens t 
                          JOIN users u ON t.user_id = u.id 
                          WHERE t.expires_at > NOW()");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $valid_token = false;
    while ($row = $result->fetch_assoc()) {
        if (password_verify($token, $row['token_hash'])) {
            // Start session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user'] = $row['username'];
            $valid_token = true;
            
            // Rotate token
            $new_token = bin2hex(random_bytes(32));
            $new_hash = password_hash($new_token, PASSWORD_DEFAULT);
            $expiry = time() + 60 * 60 * 24 * 30; // 30 days
            
            // Update token in database
            $update_stmt = $conn->prepare("UPDATE auth_tokens 
                                         SET token_hash = ?, expires_at = ? 
                                         WHERE id = ?");
            $update_stmt->bind_param("sii", $new_hash, $expiry, $row['id']);
            $update_stmt->execute();
            
            // Set new cookie
            setcookie('remember_token', $new_token, [
                'expires' => $expiry,
                'path' => '/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            break;
        }
    }
    
    // If token was invalid, clear it
    if (!$valid_token) {
        setcookie('remember_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true
        ]);
    }
}

// Additional security headers if not already set
if (!headers_sent()) {
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("X-XSS-Protection: 1; mode=block");
    header("Referrer-Policy: strict-origin-when-cross-origin");
}
?>
